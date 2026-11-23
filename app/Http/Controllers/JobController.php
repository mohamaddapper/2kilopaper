<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = JobPosting::with(['company', 'category', 'province', 'city'])
            ->where('status', 'active');

        // Search Keyword
        if ($q = trim($request->input('q', ''))) {
            $query->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                  ->orWhere('description', 'like', "%{$q}%")
                  ->orWhere('requirements', 'like', "%{$q}%")
                  ->orWhereHas('company', fn($c) => $c->where('name', 'like', "%{$q}%"));
            });
        }

        // Category filter
        if ($catId = $request->input('cat')) {
            $query->where('category_id', $catId);
        }

        // Province filter
        if ($provId = $request->input('province')) {
            $query->where('province_id', $provId);
        }

        // Workplace Type (کارخانه، دفتر مرکزی...)
        if ($wp = $request->input('workplace')) {
            $query->where('workplace_type', $wp);
        }

        // Job Type (تمام وقت، شیفتی...)
        if ($jt = $request->input('type')) {
            $query->where('job_type', $jt);
        }

        // Work Shift (شیفت روز، شب، چرخشی)
        if ($ws = $request->input('shift')) {
            $query->where('work_shift', $ws);
        }

        // Experience
        if ($exp = $request->input('exp')) {
            $query->where('experience_years', $exp);
        }

        // Perks & Benefits filters (JSON queries)
        if ($request->boolean('has_service')) {
            $query->where('benefits->has_service', true);
        }
        if ($request->boolean('has_meal')) {
            $query->where('benefits->has_meal', true);
        }
        if ($request->boolean('has_accommodation')) {
            $query->where('benefits->has_accommodation', true);
        }
        if ($request->boolean('has_bonus')) {
            $query->where('benefits->has_bonus', true);
        }

        // Sorting: Featured first, then latest
        $jobs = $query->orderBy('is_featured', 'desc')
            ->orderBy('is_immediate', 'desc')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = Category::whereNull('parent_id')->with('children')->get();
        $provinces = Province::orderBy('name')->get();

        return view('jobs.index', compact('jobs', 'categories', 'provinces'));
    }

    public function show(JobPosting $job)
    {
        if ($job->status !== 'active' && (!auth()->check() || auth()->id() !== $job->user_id)) {
            abort(404);
        }

        \App\Services\ViewTracker::track($job, 'job', $job->user_id);
        $job->load(['company', 'category', 'province', 'city', 'user']);

        // Related jobs in the same category or city
        $relatedJobs = JobPosting::where('id', '!=', $job->id)
            ->where('status', 'active')
            ->where(function ($q) use ($job) {
                if ($job->category_id) $q->where('category_id', $job->category_id);
                if ($job->province_id) $q->orWhere('province_id', $job->province_id);
            })
            ->latest()
            ->limit(4)
            ->get();

        return view('jobs.show', compact('job', 'relatedJobs'));
    }

    public function apply(Request $request, JobPosting $job)
    {
        if ($job->status !== 'active') {
            return back()->with('error', 'مهلت ارسال رزومه برای این آگهی به پایان رسیده است.');
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:191',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:191',
            'birth_year' => 'nullable|string|max:10',
            'gender' => 'nullable|string|max:20',
            'military_status' => 'nullable|string|max:50',
            'province_id' => 'nullable|exists:provinces,id',
            'city_id' => 'nullable|exists:cities,id',
            'education' => 'nullable|string|max:100',
            'experience_years' => 'nullable|string|max:50',
            'last_company' => 'nullable|string|max:191',
            'skills' => 'nullable|array',
            'expected_salary' => 'nullable|string|max:100',
            'resume_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'cover_letter' => 'nullable|string|max:2000',
        ]);

        if ($request->hasFile('resume_file')) {
            $file = $request->file('resume_file');
            $fileName = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $dest = public_path('uploads/resumes');
            if (!file_exists($dest)) {
                mkdir($dest, 0755, true);
            }
            $file->move($dest, $fileName);
            $validated['resume_file'] = 'uploads/resumes/' . $fileName;
        }

        $validated['job_posting_id'] = $job->id;
        $validated['user_id'] = auth()->id();
        $validated['status'] = 'new';

        JobApplication::create($validated);

        return back()->with('ok', 'درخواست همکاری و رزومه شما با موفقیت برای کارفرما ارسال گردید.');
    }
}
