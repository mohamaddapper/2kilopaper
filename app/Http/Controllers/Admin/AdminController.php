<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'companies_total' => Company::count(),
            'companies_pending' => Company::where('status', 'pending')->count(),
            'companies_approved' => Company::where('status', 'approved')->count(),
            'companies_gold' => Company::where('tier', 'gold')->count(),
            'companies_silver' => Company::where('tier', 'silver')->count(),
            'companies_bronze' => Company::where('tier', 'bronze')->count(),
            'jobs_total' => JobPosting::count(),
            'jobs_active' => JobPosting::where('status', 'active')->count(),
            'jobs_immediate' => JobPosting::where('status', 'active')->where('is_immediate', true)->count(),
            'jobs_views' => JobPosting::sum('views_count'),
            'applications_total' => JobApplication::count(),
            'applications_new' => JobApplication::where('status', 'new')->count(),
            'users_total' => User::count(),
        ];

        $pendingCompanies = Company::with(['category', 'city', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->limit(10)
            ->get();

        $recentJobs = JobPosting::with(['company', 'category', 'city'])
            ->latest()
            ->limit(6)
            ->get();

        $recentApplications = JobApplication::with(['jobPosting.company'])
            ->latest()
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingCompanies', 'recentJobs', 'recentApplications'));
    }

    public function companies(Request $request)
    {
        $query = Company::with(['category', 'city', 'user']);

        if ($q = trim($request->input('q', ''))) {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('phone', 'like', "%{$q}%")
                  ->orWhere('slogan', 'like', "%{$q}%")
                  ->orWhereHas('user', fn($u) => $u->where('phone', 'like', "%{$q}%"));
            });
        }

        if ($status = $request->input('status')) {
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        if ($tier = $request->input('tier')) {
            if ($tier === 'normal') {
                $query->whereNull('tier');
            } elseif ($tier !== 'all') {
                $query->where('tier', $tier);
            }
        }

        if ($catId = $request->input('cat')) {
            $query->where('category_id', $catId);
        }

        $companies = $query->latest()->paginate(20)->withQueryString();
        $categories = Category::whereNull('parent_id')->with('children')->get();

        return view('admin.companies', compact('companies', 'categories'));
    }

    public function approveCompany(Company $company)
    {
        $company->status = 'approved';
        $company->save();

        return back()->with('ok', "شرکت «{$company->name}» با موفقیت تأیید و در دایرکتوری منتشر شد.");
    }

    public function rejectCompany(Request $request, Company $company)
    {
        $company->status = 'rejected';
        $company->save();

        return back()->with('ok', "شرکت «{$company->name}» معلق/رد گردید.");
    }

    public function updateTier(Request $request, Company $company)
    {
        $tier = $request->input('tier');
        $months = (int) $request->input('months', 12);

        if (in_array($tier, ['gold', 'silver', 'bronze'])) {
            $company->tier = $tier;
            $company->tier_expires_at = now()->addMonths(max(1, $months));
        } else {
            $company->tier = null;
            $company->tier_expires_at = null;
        }

        $company->save();

        $tierNames = ['gold' => 'طلایی', 'silver' => 'نقره‌ای', 'bronze' => 'برنزی'];
        $tierText = $tier ? $tierNames[$tier] . " ({$months} ماهه)" : 'عادی';

        return back()->with('ok', "سطح اشتراک شرکت «{$company->name}» به {$tierText} تغییر یافت.");
    }

    public function destroyCompany(Company $company)
    {
        $name = $company->name;
        $company->categories()->detach();
        $company->delete();

        return back()->with('ok', "شرکت «{$name}» با موفقیت حذف گردید.");
    }

    public function jobs(Request $request)
    {
        $query = JobPosting::with(['company', 'category', 'province', 'city', 'user']);

        if ($q = trim($request->input('q', ''))) {
            $query->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                  ->orWhere('description', 'like', "%{$q}%")
                  ->orWhereHas('company', fn($c) => $c->where('name', 'like', "%{$q}%"));
            });
        }

        if ($status = $request->input('status')) {
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        if ($request->boolean('immediate')) {
            $query->where('is_immediate', true);
        }

        $jobs = $query->latest()->paginate(20)->withQueryString();

        return view('admin.jobs', compact('jobs'));
    }

    public function toggleJob(JobPosting $job)
    {
        $job->status = ($job->status === 'active') ? 'closed' : 'active';
        $job->save();

        $statusText = ($job->status === 'active') ? 'فعال شد' : 'متوقف گردید';
        return back()->with('ok', "وضعیت آگهی «{$job->title}» به {$statusText}.");
    }

    public function toggleImmediate(JobPosting $job)
    {
        $job->is_immediate = !$job->is_immediate;
        $job->save();

        $badgeText = $job->is_immediate ? 'اعطا شد' : 'حذف شد';
        return back()->with('ok', "نشان فوری آگهی «{$job->title}» {$badgeText}.");
    }

    public function destroyJob(JobPosting $job)
    {
        $job->delete();
        return back()->with('ok', "آگهی استخدام با موفقیت حذف گردید.");
    }

    public function applications(Request $request)
    {
        $query = JobApplication::with(['jobPosting.company', 'province', 'city']);

        if ($q = trim($request->input('q', ''))) {
            $query->where(function ($w) use ($q) {
                $w->where('full_name', 'like', "%{$q}%")
                  ->orWhere('phone', 'like', "%{$q}%")
                  ->orWhere('last_company', 'like', "%{$q}%");
            });
        }

        if ($status = $request->input('status')) {
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        $applications = $query->latest()->paginate(20)->withQueryString();

        return view('admin.applications', compact('applications'));
    }

    public function users(Request $request)
    {
        $query = User::withCount(['companies', 'jobPostings']);

        if ($q = trim($request->input('q', ''))) {
            $query->where('phone', 'like', "%{$q}%")
                  ->orWhere('name', 'like', "%{$q}%");
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function toggleUserAdmin(User $user)
    {
        if ($user->id === auth()->id() && $user->is_admin) {
            return back()->with('error', 'نمی‌توانید سطح دسترسی ادمین خودتان را لغو کنید!');
        }

        $user->is_admin = !$user->is_admin;
        $user->save();

        $txt = $user->is_admin ? 'ادمین سیستم گردید' : 'از سطح ادمین خارج شد';
        return back()->with('ok', "کاربر {$user->phone} {$txt}.");
    }
}
