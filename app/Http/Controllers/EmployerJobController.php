<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\Province;
use Illuminate\Http\Request;

class EmployerJobController extends Controller
{
    public function create()
    {
        $companies = auth()->user()->companies()->get();
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $provinces = Province::with('cities')->orderBy('name')->get();
        $locationsTree = Province::with('cities')->orderBy('name')->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'cities' => $p->cities->map(fn($c) => ['id' => $c->id, 'name' => $c->name]),
            ];
        });

        $job = new JobPosting();

        return view('dashboard.jobs.form', compact('companies', 'categories', 'provinces', 'locationsTree', 'job'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateJob($request);
        $validated['user_id'] = auth()->id();
        $validated['slug'] = JobPosting::makeSlug($validated['title']);
        $validated['status'] = 'active';
        $validated['expires_at'] = now()->addDays(30);

        // Process Benefits checkbox array
        $validated['benefits'] = [
            'has_service' => $request->boolean('benefits.has_service'),
            'service_routes' => $request->input('benefits.service_routes'),
            'has_meal' => $request->boolean('benefits.has_meal'),
            'meal_types' => $request->input('benefits.meal_types', []),
            'has_accommodation' => $request->boolean('benefits.has_accommodation'),
            'has_insurance' => $request->boolean('benefits.has_insurance'),
            'has_supplementary_insurance' => $request->boolean('benefits.has_supplementary_insurance'),
            'has_bonus' => $request->boolean('benefits.has_bonus'),
        ];

        // Process Skills array
        $skillsInput = $request->input('skills', []);
        if (is_string($skillsInput)) {
            $skillsInput = array_filter(array_map('trim', explode(',', $skillsInput)));
        }
        $validated['skills'] = array_values(array_filter($skillsInput));

        JobPosting::create($validated);

        return redirect()->to(route('dashboard') . '#jobs')->with('ok', 'آگهی استخدام با موفقیت ثبت و منتشر گردید.');
    }

    public function edit(JobPosting $job)
    {
        if ($job->user_id !== auth()->id()) {
            abort(403);
        }

        $companies = auth()->user()->companies()->get();
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $provinces = Province::with('cities')->orderBy('name')->get();
        $locationsTree = Province::with('cities')->orderBy('name')->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'cities' => $p->cities->map(fn($c) => ['id' => $c->id, 'name' => $c->name]),
            ];
        });

        return view('dashboard.jobs.form', compact('companies', 'categories', 'provinces', 'locationsTree', 'job'));
    }

    public function update(Request $request, JobPosting $job)
    {
        if ($job->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $this->validateJob($request);

        $validated['benefits'] = [
            'has_service' => $request->boolean('benefits.has_service'),
            'service_routes' => $request->input('benefits.service_routes'),
            'has_meal' => $request->boolean('benefits.has_meal'),
            'meal_types' => $request->input('benefits.meal_types', []),
            'has_accommodation' => $request->boolean('benefits.has_accommodation'),
            'has_insurance' => $request->boolean('benefits.has_insurance'),
            'has_supplementary_insurance' => $request->boolean('benefits.has_supplementary_insurance'),
            'has_bonus' => $request->boolean('benefits.has_bonus'),
        ];

        $skillsInput = $request->input('skills', []);
        if (is_string($skillsInput)) {
            $skillsInput = array_filter(array_map('trim', explode(',', $skillsInput)));
        }
        $validated['skills'] = array_values(array_filter($skillsInput));

        $job->update($validated);

        return redirect()->to(route('dashboard') . '#jobs')->with('ok', 'تغییرات آگهی با موفقیت ذخیره شد.');
    }

    public function toggle(JobPosting $job)
    {
        if ($job->user_id !== auth()->id()) {
            abort(403);
        }

        $job->status = ($job->status === 'active') ? 'closed' : 'active';
        $job->save();

        $msg = ($job->status === 'active') ? 'آگهی مجدداً فعال شد.' : 'آگهی متوقف گردید.';
        return back()->with('ok', $msg);
    }

    public function destroy(JobPosting $job)
    {
        if ($job->user_id !== auth()->id()) {
            abort(403);
        }

        $job->delete();

        return back()->with('ok', 'آگهی استخدام با موفقیت حذف شد.');
    }

    public function applications(JobPosting $job)
    {
        if ($job->user_id !== auth()->id()) {
            abort(403);
        }

        $job->load(['applications.province', 'applications.city', 'company']);

        foreach ($job->applications as $app) {
            \App\Services\ViewTracker::track($app, 'application', $app->user_id);
        }

        return view('dashboard.jobs.applications', compact('job'));
    }

    public function updateApplicationStatus(Request $request, JobApplication $application)
    {
        if ($application->jobPosting->user_id !== auth()->id()) {
            abort(403);
        }

        $status = $request->input('status');
        if (in_array($status, ['new', 'reviewed', 'interview', 'rejected', 'hired'])) {
            $application->status = $status;
        }

        if ($request->has('employer_note')) {
            $application->employer_note = $request->input('employer_note');
        }

        $application->save();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'status' => $application->status]);
        }

        return back()->with('ok', 'وضعیت متقاضی به‌روزرسانی شد.');
    }

    private function cleanNumericInput($val): ?int
    {
        if ($val === null || trim((string)$val) === '') {
            return null;
        }
        $persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        $arabic  = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
        $english = ['0','1','2','3','4','5','6','7','8','9'];
        $val = str_replace($persian, $english, (string)$val);
        $val = str_replace($arabic, $english, $val);
        $val = preg_replace('/[^\d]/', '', $val);
        return $val !== '' ? (int)$val : null;
    }

    private function validateJob(Request $request): array
    {
        $request->merge([
            'salary_min' => $this->cleanNumericInput($request->input('salary_min')),
            'salary_max' => $this->cleanNumericInput($request->input('salary_max')),
        ]);

        return $request->validate([
            'title' => 'required|string|max:191',
            'company_id' => 'nullable|exists:companies,id',
            'category_id' => 'nullable|exists:categories,id',
            'job_type' => 'required|string|max:50',
            'work_shift' => 'nullable|string|max:50',
            'gender' => 'required|string|max:20',
            'military_service' => 'nullable|string|max:50',
            'experience_years' => 'required|string|max:20',
            'salary_type' => 'required|string|max:50',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'province_id' => 'nullable|exists:provinces,id',
            'city_id' => 'nullable|exists:cities,id',
            'workplace_type' => 'required|string|max:50',
            'address' => 'nullable|string|max:500',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'contact_type' => 'required|string|max:50',
            'contact_phone' => 'nullable|string|max:50',
            'contact_whatsapp' => 'nullable|string|max:50',
            'is_immediate' => 'nullable|boolean',
        ]);
    }
}
