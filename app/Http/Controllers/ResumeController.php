<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Province;
use App\Models\Resume;
use App\Services\ViewTracker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ResumeController extends Controller
{
    public function index(Request $r)
    {
        $query = Resume::approvedPublic()->with(['province', 'city']);

        if ($q = trim((string) $r->input('q', ''))) {
            $like = '%' . addcslashes($q, '%_\\') . '%';
            $query->where(function ($w) use ($like) {
                $w->where('job_title', 'like', $like)
                  ->orWhere('full_name', 'like', $like)
                  ->orWhere('bio', 'like', $like);
            });
        }

        if ($spec = trim((string) $r->input('specialty', ''))) {
            $query->where('specialty', $spec);
        }

        if ($provId = $r->integer('province_id')) {
            $query->where('province_id', $provId);
        }

        if ($minExp = $r->integer('min_exp')) {
            $query->where('experience_years', '>=', $minExp);
        }

        $resumes = $query->latest()->paginate(18)->withQueryString();
        $provinces = Province::orderBy('name')->get();
        $specialties = Resume::SPECIALTIES;

        return view('resumes.index', compact('resumes', 'provinces', 'specialties'));
    }

    public function show(Resume $resume)
    {
        $user = auth()->user();
        $isOwnerOrAdmin = $user && ($user->isAdmin() || (int) $resume->user_id === (int) $user->id);

        if (!$isOwnerOrAdmin && ($resume->status !== 'approved' || $resume->availability !== 'available')) {
            abort(404);
        }

        ViewTracker::track($resume, 'resume', $resume->user_id);
        $resume->load(['province', 'city']);

        $canSeeContact = $isOwnerOrAdmin || Resume::viewerCanSeeContact($user);

        return view('resumes.show', compact('resume', 'canSeeContact'));
    }

    public function create()
    {
        $provinces = Province::with('cities')->orderBy('name')->get();
        $resume = new Resume([
            'full_name' => auth()->user()?->name ?? '',
            'phone' => auth()->user()?->phone ?? '',
            'work_type' => 'full_time',
            'availability' => 'available',
        ]);

        return view('resumes.form', compact('resume', 'provinces'));
    }

    public function store(Request $r)
    {
        $data = $this->validated($r);
        $data['user_id'] = $r->user()->id;
        $data['status'] = $r->user()->isAdmin() ? 'approved' : 'pending';
        $data['availability'] = 'available';

        if ($r->hasFile('resume_file')) {
            $data['resume_file'] = $this->uploadResumeFile($r->file('resume_file'));
        }

        $resume = Resume::create($data);

        return redirect()->route('dashboard')->with(
            'ok',
            $resume->status === 'approved'
                ? 'رزومه شما با موفقیت در بانک کارجویان منتشر شد.'
                : 'رزومه شما با موفقیت ثبت شد و پس از تأیید مدیریت در بانک کارجویان نمایش داده می‌شود.'
        );
    }

    public function edit(Resume $resume)
    {
        $user = auth()->user();
        abort_unless($user && ($user->isAdmin() || (int) $resume->user_id === (int) $user->id), 403);

        $provinces = Province::with('cities')->orderBy('name')->get();

        return view('resumes.form', compact('resume', 'provinces'));
    }

    public function update(Request $r, Resume $resume)
    {
        $user = $r->user();
        abort_unless($user && ($user->isAdmin() || (int) $resume->user_id === (int) $user->id), 403);

        $data = $this->validated($r);
        if ($r->hasFile('resume_file')) {
            $data['resume_file'] = $this->uploadResumeFile($r->file('resume_file'));
        }

        $resume->update($data);

        return redirect()->route('dashboard')->with('ok', 'رزومه با موفقیت ویرایش و ذخیره شد.');
    }

    public function toggleAvailability(Resume $resume)
    {
        $user = auth()->user();
        abort_unless($user && ($user->isAdmin() || (int) $resume->user_id === (int) $user->id), 403);

        $resume->availability = $resume->availability === 'available' ? 'hired' : 'available';
        $resume->save();

        $msg = $resume->availability === 'available'
            ? 'وضعیت رزومه به «آماده به کار» تغییر یافت.'
            : 'وضعیت رزومه به «مشغول به کار (مخفی)» تغییر یافت.';

        return back()->with('ok', $msg);
    }

    public function destroy(Resume $resume)
    {
        $user = auth()->user();
        abort_unless($user && ($user->isAdmin() || (int) $resume->user_id === (int) $user->id), 403);

        $resume->delete();
        return back()->with('ok', 'رزومه با موفقیت حذف شد.');
    }

    private function validated(Request $r): array
    {
        $data = $r->validate([
            'full_name' => 'required|string|max:150',
            'phone' => 'required|string|max:30',
            'job_title' => 'required|string|max:190',
            'specialty' => 'nullable|string|max:100',
            'machines' => 'nullable|array',
            'machines.*' => 'string|max:120',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'nullable|exists:cities,id',
            'experience_years' => 'nullable|integer|min:0|max:60',
            'work_type' => 'required|in:full_time,shift_work,part_time,project',
            'needs_accommodation' => 'nullable|boolean',
            'expected_salary' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:3000',
            'resume_file' => 'nullable|file|mimes:pdf|max:5120',
        ], [
            'full_name.required' => 'نام و نام خانوادگی الزامی است.',
            'phone.required' => 'شماره تماس الزامی است.',
            'job_title.required' => 'عنوان شغلی یا تخصص خود را وارد کنید.',
            'province_id.required' => 'انتخاب استان محل سکونت/کار الزامی است.',
            'resume_file.mimes' => 'فایل رزومه باید با فرمت PDF باشد.',
            'resume_file.max' => 'حجم فایل رزومه نباید بیشتر از ۵ مگابایت باشد.',
        ]);

        $data['needs_accommodation'] = $r->boolean('needs_accommodation');
        $data['experience_years'] = (int) ($data['experience_years'] ?? 0);
        $data['machines'] = array_values(array_filter((array) ($data['machines'] ?? [])));

        return $data;
    }

    private function uploadResumeFile($file): ?string
    {
        if (!$file || !$file->isValid()) {
            return null;
        }
        $dir = public_path('uploads/resumes');
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
        $name = time() . '_' . Str::random(10) . '.pdf';
        $file->move($dir, $name);
        return 'uploads/resumes/' . $name;
    }
}
