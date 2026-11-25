<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\Province;
use App\Models\User;
use App\Services\ViewTracker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

        AdminLog::record('company.approve', "تأیید و انتشار شرکت «{$company->name}»", 'company', $company->id);
        return back()->with('ok', "شرکت «{$company->name}» با موفقیت تأیید و در دایرکتوری منتشر شد.");
    }

    public function rejectCompany(Request $request, Company $company)
    {
        $company->status = 'rejected';
        $company->save();

        AdminLog::record('company.reject', "رد / تعلیق شرکت «{$company->name}»", 'company', $company->id);
        return back()->with('ok', "شرکت «{$company->name}» معلق/رد گردید.");
    }

    public function updateCompanyNote(Request $request, Company $company)
    {
        $validated = $request->validate([
            'admin_note' => 'nullable|string|max:2000',
        ]);
        $company->admin_note = trim((string) ($validated['admin_note'] ?? '')) ?: null;
        $company->save();

        AdminLog::record('company.note', "به‌روزرسانی یادداشت داخلی شرکت «{$company->name}»", 'company', $company->id);
        return back()->with('ok', "یادداشت داخلی برای «{$company->name}» ذخیره شد.");
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

        AdminLog::record('company.tier', "تغییر اشتراک شرکت «{$company->name}» به {$tierText}", 'company', $company->id);
        return back()->with('ok', "سطح اشتراک شرکت «{$company->name}» به {$tierText} تغییر یافت.");
    }

    public function destroyCompany(Company $company)
    {
        $name = $company->name;
        $id = $company->id;
        $company->categories()->detach();
        $company->delete();

        AdminLog::record('company.delete', "حذف دائمی شرکت «{$name}»", 'company', $id);
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
        AdminLog::record('job.toggle', "تغییر وضعیت آگهی «{$job->title}» به {$statusText}", 'job', $job->id);
        return back()->with('ok', "وضعیت آگهی «{$job->title}» به {$statusText}.");
    }

    public function toggleImmediate(JobPosting $job)
    {
        $job->is_immediate = !$job->is_immediate;
        $job->save();

        $badgeText = $job->is_immediate ? 'اعطا شد' : 'حذف شد';
        AdminLog::record('job.immediate', "نشان فوری آگهی «{$job->title}» {$badgeText}", 'job', $job->id);
        return back()->with('ok', "نشان فوری آگهی «{$job->title}» {$badgeText}.");
    }

    public function destroyJob(JobPosting $job)
    {
        $title = $job->title;
        $id = $job->id;
        $job->delete();
        AdminLog::record('job.delete', "حذف آگهی استخدام «{$title}»", 'job', $id);
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
        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            return back()->with('error', 'فقط مدیر ارشد می‌تواند سطح دسترسی مدیر ارشد دیگری را تغییر دهد.');
        }

        $user->is_admin = !$user->is_admin;
        if (Schema::hasColumn('users', 'admin_role')) {
            if ($user->is_admin && empty($user->admin_role)) {
                $user->admin_role = 'moderator';
                $user->admin_permissions = User::ROLE_PRESETS['moderator']['permissions'];
            }
            if (!$user->is_admin) {
                $user->admin_role = null;
                $user->admin_permissions = null;
            }
        }
        $user->save();

        $txt = $user->is_admin ? 'به تیم مدیریت اضافه شد' : 'از سطح مدیریت خارج شد';
        AdminLog::record('user.toggle_admin', "کاربر {$user->phone} {$txt}", 'user', $user->id);
        return back()->with('ok', "کاربر {$user->phone} {$txt}.");
    }

    public function updateUser(Request $request, User $user)
    {
        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            return back()->with('error', 'فقط مدیر ارشد می‌تواند اطلاعات مدیر ارشد دیگری را ویرایش کند.');
        }

        $validated = $request->validate([
            'name' => 'nullable|string|max:190',
            'phone' => 'required|string|max:30|unique:users,phone,' . $user->id,
            'is_admin' => 'nullable|boolean',
            'admin_role' => 'nullable|string|in:super_admin,moderator,seo,sales,custom',
            'admin_permissions' => 'nullable|array',
            'admin_permissions.*' => 'string',
        ], [
            'phone.required' => 'شماره تماس الزامی است.',
            'phone.unique' => 'این شماره تماس قبلاً ثبت شده است.',
        ]);

        $user->name = $validated['name'] ?: null;
        $user->phone = $validated['phone'];

        if ($user->id !== auth()->id()) {
            $isAdmin = $request->boolean('is_admin');
            $user->is_admin = $isAdmin;

            if (Schema::hasColumn('users', 'admin_role')) {
                if ($isAdmin) {
                    $role = $validated['admin_role'] ?? 'custom';
                    if ($role === 'super_admin' && !auth()->user()->isSuperAdmin()) {
                        $role = 'moderator';
                    }
                    $user->admin_role = $role;

                    $allowedKeys = array_keys(User::ALL_PERMISSIONS);
                    $selectedPerms = array_values(array_intersect((array) ($validated['admin_permissions'] ?? []), $allowedKeys));
                    if ($role !== 'custom' && empty($selectedPerms) && isset(User::ROLE_PRESETS[$role])) {
                        $selectedPerms = User::ROLE_PRESETS[$role]['permissions'];
                    }
                    $user->admin_permissions = $selectedPerms;
                } else {
                    $user->admin_role = null;
                    $user->admin_permissions = null;
                }
            }
        }

        $user->save();

        AdminLog::record('user.update', "ویرایش اطلاعات و دسترسی کاربر {$user->phone}", 'user', $user->id);
        return back()->with('ok', "اطلاعات کاربر {$user->phone} با موفقیت ویرایش شد.");
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'نمی‌توانید حساب کاربری خودتان را حذف کنید!');
        }
        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            return back()->with('error', 'امکان حذف مدیر ارشد وجود ندارد.');
        }

        $phone = $user->phone;
        $id = $user->id;
        $user->delete();

        AdminLog::record('user.delete', "حذف کاربر {$phone}", 'user', $id);
        return back()->with('ok', "کاربر {$phone} با موفقیت حذف شد.");
    }

    public function logs(Request $request)
    {
        $admins = User::where('is_admin', true)->orWhere('id', 1)->orderBy('name')->get();

        if (!Schema::hasTable('admin_logs')) {
            $logs = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 30);
            return view('admin.logs', compact('logs', 'admins'));
        }

        $query = AdminLog::with('user')->latest('created_at');

        if ($uid = $request->input('user_id')) {
            $query->where('user_id', (int) $uid);
        }
        if ($act = trim((string) $request->input('action', ''))) {
            $query->where('action', 'like', $act . '%');
        }

        $logs = $query->paginate(30)->withQueryString();

        return view('admin.logs', compact('logs', 'admins'));
    }

    public function categories(Request $request)
    {
        $query = Category::with('parent.parent.parent')->withCount('companies');

        if ($q = trim($request->input('q', ''))) {
            $query->where('name', 'like', "%{$q}%")->orWhere('slug', 'like', "%{$q}%");
        }

        if ($pillarId = (int) $request->input('pillar', 0)) {
            $pillar = Category::find($pillarId);
            if ($pillar) {
                $query->whereIn('id', $pillar->allDescendantIds());
            }
        }

        $categories = $query->orderBy('sort')->paginate(30)->withQueryString();
        $pillars = Category::whereNull('parent_id')->orderBy('sort')->get();

        return view('admin.categories.index', compact('categories', 'pillars'));
    }

    public function editCategory(Category $category)
    {
        $category->load('parent.parent.parent');
        return view('admin.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:190',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:20000',
            'faqs' => 'nullable|array',
            'faqs.*.q' => 'nullable|string|max:300',
            'faqs.*.a' => 'nullable|string|max:2000',
        ], [
            'name.required' => 'نام دسته‌بندی الزامی است.',
        ]);

        $faqs = [];
        foreach ((array) ($validated['faqs'] ?? []) as $item) {
            $q = trim((string) ($item['q'] ?? ''));
            $a = trim((string) ($item['a'] ?? ''));
            if ($q !== '' && $a !== '') {
                $faqs[] = ['q' => $q, 'a' => $a];
            }
        }

        $category->update([
            'name' => $validated['name'],
            'seo_title' => $validated['seo_title'] ?: null,
            'seo_description' => $validated['seo_description'] ?: null,
            'description' => $validated['description'] ?: null,
            'faqs' => !empty($faqs) ? $faqs : null,
        ]);

        AdminLog::record('category.update', "ویرایش سئو و محتوای دسته «{$category->name}»", 'category', $category->id);
        return redirect()->route('admin.categories')->with('ok', "تنظیمات سئو و محتوای دسته «{$category->name}» با موفقیت ذخیره شد.");
    }

    public function analytics()
    {
        $hasDaily = Schema::hasTable('daily_views');
        $hasInteractions = Schema::hasTable('company_interactions');

        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();
        $weekStart = now()->subDays(6)->toDateString();
        $prevWeekStart = now()->subDays(13)->toDateString();
        $prevWeekEnd = now()->subDays(7)->toDateString();
        $monthStart = now()->subDays(29)->toDateString();

        $kpi = [
            'today_total' => 0,
            'today_unique' => 0,
            'yesterday_total' => 0,
            'yesterday_unique' => 0,
            'week_total' => 0,
            'prev_week_total' => 0,
            'week_growth' => 0,
            'month_total' => 0,
            'month_unique' => 0,
        ];

        $sources = [
            'google' => 0,
            'search' => 0,
            'internal' => 0,
            'direct' => 0,
        ];

        $topCompanies = collect();
        $topCategories = collect();
        $topProvinces = collect();
        $topJobs = collect();
        $leadTotals = ['phone' => 0, 'whatsapp' => 0, 'website' => 0, 'bookmark' => 0];

        if ($hasDaily) {
            $todayRow = DB::table('daily_views')
                ->where('subject_type', 'site')->where('subject_id', 0)->where('view_date', $today)->first();
            $yesterdayRow = DB::table('daily_views')
                ->where('subject_type', 'site')->where('subject_id', 0)->where('view_date', $yesterday)->first();

            $weekRow = DB::table('daily_views')
                ->selectRaw('COALESCE(SUM(total_views),0) as total')
                ->where('subject_type', 'site')->where('subject_id', 0)
                ->where('view_date', '>=', $weekStart)->first();

            $prevWeekRow = DB::table('daily_views')
                ->selectRaw('COALESCE(SUM(total_views),0) as total')
                ->where('subject_type', 'site')->where('subject_id', 0)
                ->whereBetween('view_date', [$prevWeekStart, $prevWeekEnd])->first();

            $monthRow = DB::table('daily_views')
                ->selectRaw('COALESCE(SUM(total_views),0) as total, COALESCE(SUM(unique_views),0) as uniq, COALESCE(SUM(source_google),0) as google, COALESCE(SUM(source_search),0) as search, COALESCE(SUM(source_internal),0) as internal, COALESCE(SUM(source_direct),0) as direct')
                ->where('subject_type', 'site')->where('subject_id', 0)
                ->where('view_date', '>=', $monthStart)->first();

            $weekTotal = (int) ($weekRow->total ?? 0);
            $prevWeekTotal = (int) ($prevWeekRow->total ?? 0);
            $growth = $prevWeekTotal > 0 ? round((($weekTotal - $prevWeekTotal) / $prevWeekTotal) * 100) : ($weekTotal > 0 ? 100 : 0);

            $kpi = [
                'today_total' => (int) ($todayRow->total_views ?? 0),
                'today_unique' => (int) ($todayRow->unique_views ?? 0),
                'yesterday_total' => (int) ($yesterdayRow->total_views ?? 0),
                'yesterday_unique' => (int) ($yesterdayRow->unique_views ?? 0),
                'week_total' => $weekTotal,
                'prev_week_total' => $prevWeekTotal,
                'week_growth' => $growth,
                'month_total' => (int) ($monthRow->total ?? 0),
                'month_unique' => (int) ($monthRow->uniq ?? 0),
            ];

            $sources = [
                'google' => (int) ($monthRow->google ?? 0),
                'search' => (int) ($monthRow->search ?? 0),
                'internal' => (int) ($monthRow->internal ?? 0),
                'direct' => (int) ($monthRow->direct ?? 0),
            ];

            // Top 10 companies in last 30 days
            $companyViewRows = DB::table('daily_views')
                ->selectRaw('subject_id, SUM(total_views) as views_30d, SUM(unique_views) as unique_30d')
                ->where('subject_type', 'company')
                ->where('view_date', '>=', $monthStart)
                ->groupBy('subject_id')
                ->orderByDesc('views_30d')
                ->limit(10)
                ->get()
                ->keyBy('subject_id');

            if ($companyViewRows->isNotEmpty()) {
                $companiesMap = Company::with('category')->whereIn('id', $companyViewRows->keys())->get()->keyBy('id');
                $interactionsMap = $hasInteractions
                    ? DB::table('company_interactions')
                        ->selectRaw('company_id, SUM(phone_clicks) as phone, SUM(whatsapp_clicks) as whatsapp, SUM(website_clicks) as website')
                        ->whereIn('company_id', $companyViewRows->keys())
                        ->where('stat_date', '>=', $monthStart)
                        ->groupBy('company_id')
                        ->get()->keyBy('company_id')
                    : collect();

                foreach ($companyViewRows as $cid => $row) {
                    if ($c = $companiesMap->get($cid)) {
                        $c->views_30d = (int) $row->views_30d;
                        $c->unique_30d = (int) $row->unique_30d;
                        $ci = $interactionsMap->get($cid);
                        $c->leads_30d = $ci ? ((int) $ci->phone + (int) $ci->whatsapp + (int) $ci->website) : 0;
                        $topCompanies->push($c);
                    }
                }
            }

            // Top 5 categories
            $catViewRows = DB::table('daily_views')
                ->selectRaw('subject_id, SUM(total_views) as views_30d')
                ->where('subject_type', 'category')
                ->where('view_date', '>=', $monthStart)
                ->groupBy('subject_id')
                ->orderByDesc('views_30d')
                ->limit(5)
                ->get();
            if ($catViewRows->isNotEmpty()) {
                $catsMap = Category::whereIn('id', $catViewRows->pluck('subject_id'))->get()->keyBy('id');
                foreach ($catViewRows as $row) {
                    if ($cat = $catsMap->get($row->subject_id)) {
                        $cat->views_30d = (int) $row->views_30d;
                        $topCategories->push($cat);
                    }
                }
            }

            // Top 5 provinces
            $provViewRows = DB::table('daily_views')
                ->selectRaw('subject_id, SUM(total_views) as views_30d')
                ->where('subject_type', 'province')
                ->where('view_date', '>=', $monthStart)
                ->groupBy('subject_id')
                ->orderByDesc('views_30d')
                ->limit(5)
                ->get();
            if ($provViewRows->isNotEmpty()) {
                $provsMap = Province::whereIn('id', $provViewRows->pluck('subject_id'))->get()->keyBy('id');
                foreach ($provViewRows as $row) {
                    if ($prov = $provsMap->get($row->subject_id)) {
                        $prov->views_30d = (int) $row->views_30d;
                        $topProvinces->push($prov);
                    }
                }
            }

            // Top 5 jobs
            $jobViewRows = DB::table('daily_views')
                ->selectRaw('subject_id, SUM(total_views) as views_30d')
                ->where('subject_type', 'job')
                ->where('view_date', '>=', $monthStart)
                ->groupBy('subject_id')
                ->orderByDesc('views_30d')
                ->limit(5)
                ->get();
            if ($jobViewRows->isNotEmpty()) {
                $jobsMap = JobPosting::with('company')->whereIn('id', $jobViewRows->pluck('subject_id'))->get()->keyBy('id');
                foreach ($jobViewRows as $row) {
                    if ($job = $jobsMap->get($row->subject_id)) {
                        $job->views_30d = (int) $row->views_30d;
                        $topJobs->push($job);
                    }
                }
            }
        }

        // Fallback to overall views_count if no 30-day company rows yet
        if ($topCompanies->isEmpty()) {
            $topCompanies = Company::approved()->with('category')->orderByDesc('views_count')->limit(10)->get()
                ->map(function ($c) {
                    $c->views_30d = (int) ($c->views_count ?? 0);
                    $c->unique_30d = (int) ($c->views_count ?? 0);
                    $c->leads_30d = 0;
                    return $c;
                });
        }
        if ($topJobs->isEmpty()) {
            $topJobs = JobPosting::with('company')->orderByDesc('views_count')->limit(5)->get()
                ->map(function ($j) {
                    $j->views_30d = (int) ($j->views_count ?? 0);
                    return $j;
                });
        }

        if ($hasInteractions) {
            $leadRow = DB::table('company_interactions')
                ->selectRaw('COALESCE(SUM(phone_clicks),0) as phone, COALESCE(SUM(whatsapp_clicks),0) as whatsapp, COALESCE(SUM(website_clicks),0) as website, COALESCE(SUM(bookmark_clicks),0) as bookmark')
                ->where('stat_date', '>=', $monthStart)->first();
            $leadTotals = [
                'phone' => (int) ($leadRow->phone ?? 0),
                'whatsapp' => (int) ($leadRow->whatsapp ?? 0),
                'website' => (int) ($leadRow->website ?? 0),
                'bookmark' => (int) ($leadRow->bookmark ?? 0),
            ];
        }

        $series = ViewTracker::getDailySeries('site', 0, 30);

        return view('admin.analytics', compact(
            'kpi',
            'series',
            'sources',
            'topCompanies',
            'topCategories',
            'topProvinces',
            'topJobs',
            'leadTotals'
        ));
    }
}
