<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SiteController extends Controller
{
    public function home(Request $r)
    {
        if ($p = (int) $r->query('p')) {
            $company = Company::find($p);
            if ($company && $company->status === 'approved') {
                return redirect()->route('company', $company);
            }
        }

        $categories = Cache::remember('home.categories', 600, fn () => Category::whereNull('parent_id')->orderBy('sort')->get());
        
        // ۸ رسته صنعتی اصلی بر اساس بیشترین شرکت‌ها
        $coreCategories = Cache::remember('home.core_categories', 600, function () {
            return Category::withCount('companies')
                ->whereIn('id', [41, 130, 124, 128, 129, 40, 62, 56])
                ->orderByDesc('companies_count')
                ->get();
        });
        if ($coreCategories->isEmpty()) {
            $coreCategories = Category::withCount('companies')->orderByDesc('companies_count')->take(8)->get();
        }

        // ۱۲ قطب صنعتی و استان‌های برتر کشور
        $topProvinces = Cache::remember('home.top_provinces', 600, function () {
            return Province::withCount('companies')
                ->orderByDesc('companies_count')
                ->take(12)
                ->get();
        });

        // شرکت‌های ویژه و اشتراک‌دار (طلایی، نقره‌ای، برنزی)
        $featured = Company::approved()
            ->whereNotNull('tier')
            ->ranked()
            ->with(['city', 'category', 'province', 'categories', 'bookmarks'])
            ->limit(18)
            ->get();
        
        if ($featured->count() < 12) {
            $featured = Company::approved()
                ->ranked()
                ->with(['city', 'category', 'province', 'categories', 'bookmarks'])
                ->limit(18)
                ->get();
        }

        // شرکت‌های تخصصی برای تب‌های تفکیکی (اسلایدرهای ۶ تایی صفحه اصلی)
        $cartonCompanies = Company::approved()
            ->whereHas('category', fn($q) => $q->where('name', 'like', '%کارتن%')->orWhere('name', 'like', '%ورق%'))
            ->ranked()
            ->with(['city', 'category', 'province', 'categories', 'bookmarks'])
            ->limit(18)
            ->get();

        $paperCompanies = Company::approved()
            ->whereHas('category', fn($q) => $q->where('name', 'like', '%کاغذ%')->orWhere('name', 'like', '%مقوا%'))
            ->ranked()
            ->with(['city', 'category', 'province', 'categories', 'bookmarks'])
            ->limit(18)
            ->get();

        $machineryCompanies = Company::approved()
            ->whereHas('category', fn($q) => $q->where('name', 'like', '%ماشین%'))
            ->ranked()
            ->with(['city', 'category', 'province', 'categories', 'bookmarks'])
            ->limit(18)
            ->get();

        $printCompanies = Company::approved()
            ->whereHas('category', fn($q) => $q->where('name', 'like', '%چاپ%'))
            ->ranked()
            ->with(['city', 'category', 'province', 'categories', 'bookmarks'])
            ->limit(18)
            ->get();

        // فرصت‌های شغلی و رزومه‌های کارجویان
        $hasJobsTable = false;
        try {
            $hasJobsTable = \Illuminate\Support\Facades\Schema::hasTable('job_postings');
        } catch (\Throwable $e) {}

        $recentJobs = $hasJobsTable ? JobPosting::with(['company', 'city', 'province'])->latest()->take(4)->get() : collect();
        $recentResumes = $hasJobsTable ? JobApplication::with(['province', 'city'])->latest()->take(4)->get() : collect();

        // استان‌ها برای فیلتر جستجو در صفحه اول
        $searchProvinces = Province::orderBy('name')->get();

        return view('home', compact(
            'categories',
            'coreCategories',
            'topProvinces',
            'featured',
            'cartonCompanies',
            'paperCompanies',
            'machineryCompanies',
            'printCompanies',
            'recentJobs',
            'recentResumes',
            'searchProvinces'
        ));
    }

    public function directory(Request $r)
    {
        $isAjax = $r->ajax() || $r->wantsJson() || $r->boolean('ajax');

        // ریدایرکت ۳۰۱ فیلترهای تکی دسته یا استان به صفحات اختصاصی سئوشده
        if (!$isAjax && !$r->filled('q') && !$r->filled('city') && !$r->boolean('featured')) {
            if ($r->filled('cat') && !$r->filled('province')) {
                if ($cat = Category::with('parent.parent.parent')->find((int) $r->query('cat'))) {
                    return redirect()->to($cat->url, 301);
                }
            }
            if ($r->filled('province') && !$r->filled('cat')) {
                if ($prov = Province::find((int) $r->query('province'))) {
                    return redirect()->to($prov->url, 301);
                }
            }
        }

        $q = Company::approved()->with(['city', 'category', 'province', 'categories', 'bookmarks']);
        if ($s = trim((string) $r->query('q'))) {
            $like = '%' . addcslashes($s, '%_\\') . '%';
            $q->where(fn ($w) => $w->where('name', 'like', $like)->orWhere('description', 'like', $like));
        }
        if ($r->boolean('featured') || $r->query('featured') == '1' || $r->query('tier') === 'featured') {
            $q->whereNotNull('tier')->where(function ($sub) {
                $sub->whereNull('tier_expires_at')->orWhere('tier_expires_at', '>', now());
            });
        }
        if ($r->filled('province')) {
            $q->where('province_id', (int) $r->query('province'));
        }
        if ($r->filled('city')) $q->where('city_id', (int) $r->query('city'));
        if ($r->filled('cat')) {
            $cat = Category::find((int) $r->query('cat'));
            $ids = $cat ? $cat->children()->pluck('id')->push($cat->id) : collect();
            $q->where(function ($subQ) use ($ids) {
                $subQ->whereIn('category_id', $ids)
                     ->orWhereHas('categories', fn($cQ) => $cQ->whereIn('categories.id', $ids));
            });
        }

        $companies = $q->ranked()->paginate(18)->withQueryString();

        // پاسخ ایجکس برای بارگذاری خودکار و نامحدود در فرانت‌اند (Infinite Scroll)
        if ($r->ajax() || $r->wantsJson() || $r->boolean('ajax')) {
            $html = '';
            foreach ($companies as $company) {
                $html .= view('partials.card', compact('company'))->render();
            }
            return response()->json([
                'html' => $html,
                'has_more' => $companies->hasMorePages(),
                'next_page_url' => $companies->nextPageUrl(),
                'current_page' => $companies->currentPage(),
                'total' => $companies->total(),
            ]);
        }

        return view('directory', [
            'companies' => $companies,
            'cities' => City::orderBy('name')->get(),
            'provinces' => Province::orderBy('name')->get(),
            'categories' => Category::whereNull('parent_id')->with('children')->orderBy('sort')->get(),
        ]);
    }

    public function company(Request $request, $role, $category, Company $company)
    {
        abort_unless($company->status === 'approved', 404);

        // اعتبارسنجی ساختار یکپارچه URL: هدایت ریدایرکت 301 در صورت عدم تطابق با نقش یا دسته اصلی
        $inputRole = urldecode((string) $role);
        $inputCat = urldecode((string) $category);
        $canonicalRole = $company->activity_role;
        $canonicalCat = $company->main_category_slug;

        if ($inputRole !== $canonicalRole || $inputCat !== $canonicalCat) {
            return redirect()->to($company->url, 301);
        }

        \App\Services\ViewTracker::track($company, 'company', $company->user_id);
        $company->load(['city', 'category.parent.parent.parent', 'province', 'categories.parent.parent.parent']);

        // یافتن شرکت‌های مشابه و هم‌رسته بر اساس دسته‌بندی و زمینه‌های فعالیت مشترک
        $categoryIds = $company->categories->pluck('id')->push($company->category_id)->filter()->unique();

        $similarCompanies = Company::approved()
            ->where('id', '!=', $company->id)
            ->where(function ($q) use ($categoryIds) {
                $q->whereIn('category_id', $categoryIds)
                  ->orWhereHas('categories', fn($cQ) => $cQ->whereIn('categories.id', $categoryIds));
            })
            ->ranked()
            ->with(['city', 'category', 'province', 'categories', 'bookmarks'])
            ->limit(18)
            ->get();

        // اگر تعداد شرکت‌های مشابه برای چرخش اسلایدر کم بود، شرکت‌های فعال دیگر اضافه شوند
        if ($similarCompanies->count() < 12) {
            $excludeIds = $similarCompanies->pluck('id')->push($company->id);
            $extraCompanies = Company::approved()
                ->whereNotIn('id', $excludeIds)
                ->ranked()
                ->with(['city', 'category', 'province', 'categories', 'bookmarks'])
                ->limit(18 - $similarCompanies->count())
                ->get();
            $similarCompanies = $similarCompanies->concat($extraCompanies);
        }

        return view('company', compact('company', 'similarCompanies'));
    }

    public function legacyCompany(Company $company)
    {
        abort_unless($company->status === 'approved', 404);
        return redirect()->to($company->url, 301);
    }

    public function categoriesIndex()
    {
        $pillars = Category::whereNull('parent_id')
            ->with(['children' => fn($q) => $q->orderBy('sort')->with(['children' => fn($q2) => $q2->orderBy('sort')])])
            ->orderBy('sort')
            ->get();

        // شمارش شرکت‌های تاییدشده برای هر دسته (از ستون category_id و جدول company_categories)
        $primaryCounts = \Illuminate\Support\Facades\DB::table('companies')
            ->where('status', 'approved')
            ->whereNotNull('category_id')
            ->selectRaw('category_id, COUNT(*) as cnt')
            ->groupBy('category_id')
            ->pluck('cnt', 'category_id');

        $pivotCounts = \Illuminate\Support\Facades\DB::table('company_categories')
            ->join('companies', 'companies.id', '=', 'company_categories.company_id')
            ->where('companies.status', 'approved')
            ->selectRaw('company_categories.category_id, COUNT(DISTINCT company_categories.company_id) as cnt')
            ->groupBy('company_categories.category_id')
            ->pluck('cnt', 'category_id');

        $countsByCatId = [];
        foreach ($primaryCounts as $id => $cnt) {
            $countsByCatId[$id] = (int) $cnt;
        }
        foreach ($pivotCounts as $id => $cnt) {
            $countsByCatId[$id] = max($countsByCatId[$id] ?? 0, (int) $cnt);
        }

        $totalCompanies = Company::approved()->count();

        return view('categories.index', compact('pillars', 'countsByCatId', 'totalCompanies'));
    }

    public function categoryRole(Request $r, string $role)
    {
        $pillar = $this->resolvePillar($role);
        abort_unless($pillar, 404);

        if (urldecode($role) !== $pillar->role_slug) {
            return redirect()->to($pillar->url, 301);
        }

        return $this->renderCategoryPage($r, $pillar, null);
    }

    public function categoryShow(Request $r, string $role, string $category)
    {
        $pillar = $this->resolvePillar($role);
        abort_unless($pillar, 404);

        $cat = $this->resolveCategoryUnderPillar($pillar, $category);
        abort_unless($cat, 404);

        if (urldecode($role) !== $cat->role_slug || urldecode($category) !== slugify($cat->name)) {
            return redirect()->to($cat->url, 301);
        }

        return $this->renderCategoryPage($r, $cat, null);
    }

    public function categoryProvinceShow(Request $r, string $role, string $category, string $province)
    {
        $pillar = $this->resolvePillar($role);
        abort_unless($pillar, 404);

        $cat = $this->resolveCategoryUnderPillar($pillar, $category);
        abort_unless($cat, 404);

        $prov = $this->resolveProvince($province);
        abort_unless($prov, 404);

        $canonicalUrl = route('categories.province', [
            'role' => $cat->role_slug,
            'category' => slugify($cat->name),
            'province' => slugify($prov->name),
        ]);

        if (
            urldecode($role) !== $cat->role_slug ||
            urldecode($category) !== slugify($cat->name) ||
            urldecode($province) !== slugify($prov->name)
        ) {
            return redirect()->to($canonicalUrl, 301);
        }

        return $this->renderCategoryPage($r, $cat, $prov);
    }

    public function locationsIndex()
    {
        $provinces = Province::withCount(['companies' => fn($q) => $q->approved()])
            ->orderByDesc('companies_count')
            ->orderBy('name')
            ->get();

        $totalCompanies = Company::approved()->count();

        return view('locations.index', compact('provinces', 'totalCompanies'));
    }

    public function locationShow(Request $r, string $province)
    {
        $prov = $this->resolveProvince($province);
        abort_unless($prov, 404);

        if (urldecode($province) !== slugify($prov->name)) {
            return redirect()->to($prov->url, 301);
        }

        $q = Company::approved()
            ->where('province_id', $prov->id)
            ->with(['city', 'category', 'province', 'categories', 'bookmarks'])
            ->ranked();

        $companies = $q->paginate(18)->withQueryString();

        if ($r->ajax() || $r->wantsJson() || $r->boolean('ajax')) {
            $html = '';
            foreach ($companies as $company) {
                $html .= view('partials.card', compact('company'))->render();
            }
            return response()->json([
                'html' => $html,
                'has_more' => $companies->hasMorePages(),
                'next_page_url' => $companies->nextPageUrl(),
                'current_page' => $companies->currentPage(),
                'total' => $companies->total(),
            ]);
        }

        // دسته‌های برتر فعال در این استان برای لینک‌سازی داخلی و سئو محلی
        $topCategoryIds = Company::approved()
            ->where('province_id', $prov->id)
            ->whereNotNull('category_id')
            ->selectRaw('category_id, COUNT(*) as cnt')
            ->groupBy('category_id')
            ->orderByDesc('cnt')
            ->limit(16)
            ->pluck('cnt', 'category_id');

        $topCategories = Category::with('parent.parent.parent')
            ->whereIn('id', $topCategoryIds->keys())
            ->get()
            ->sortByDesc(fn($c) => $topCategoryIds[$c->id] ?? 0)
            ->values();

        $otherProvinces = Province::withCount(['companies' => fn($q) => $q->approved()])
            ->where('id', '!=', $prov->id)
            ->having('companies_count', '>', 0)
            ->orderByDesc('companies_count')
            ->limit(15)
            ->get();

        return view('locations.show', [
            'province' => $prov,
            'companies' => $companies,
            'topCategories' => $topCategories,
            'topCategoryCounts' => $topCategoryIds,
            'otherProvinces' => $otherProvinces,
        ]);
    }

    private function renderCategoryPage(Request $r, Category $category, ?Province $province = null)
    {
        $ids = $category->allDescendantIds();

        $q = Company::approved()
            ->where(function ($subQ) use ($ids) {
                $subQ->whereIn('category_id', $ids)
                     ->orWhereHas('categories', fn($cQ) => $cQ->whereIn('categories.id', $ids));
            })
            ->when($province, fn($w) => $w->where('province_id', $province->id))
            ->with(['city', 'category', 'province', 'categories', 'bookmarks'])
            ->ranked();

        $companies = $q->paginate(18)->withQueryString();

        if ($r->ajax() || $r->wantsJson() || $r->boolean('ajax')) {
            $html = '';
            foreach ($companies as $company) {
                $html .= view('partials.card', compact('company'))->render();
            }
            return response()->json([
                'html' => $html,
                'has_more' => $companies->hasMorePages(),
                'next_page_url' => $companies->nextPageUrl(),
                'current_page' => $companies->currentPage(),
                'total' => $companies->total(),
            ]);
        }

        $category->loadMissing(['parent.parent.parent', 'children']);
        $pillar = $category->rootPillar();

        // زیردسته‌ها یا دسته‌های هم‌سطح برای ناوبری داخلی
        $relatedCategories = $category->children->isNotEmpty()
            ? $category->children
            : ($category->parent ? $category->parent->children()->where('id', '!=', $category->id)->get() : collect());
        $relatedCategories->load('parent.parent.parent');

        // استان‌های فعال در این دسته برای فیلتر سئومحور محلی
        $provinceCounts = Company::approved()
            ->where(function ($subQ) use ($ids) {
                $subQ->whereIn('category_id', $ids)
                     ->orWhereHas('categories', fn($cQ) => $cQ->whereIn('categories.id', $ids));
            })
            ->whereNotNull('province_id')
            ->selectRaw('province_id, COUNT(*) as cnt')
            ->groupBy('province_id')
            ->orderByDesc('cnt')
            ->pluck('cnt', 'province_id');

        $activeProvinces = Province::whereIn('id', $provinceCounts->keys())
            ->get()
            ->sortByDesc(fn($p) => $provinceCounts[$p->id] ?? 0)
            ->values();

        return view('categories.show', compact(
            'category',
            'pillar',
            'province',
            'companies',
            'relatedCategories',
            'activeProvinces',
            'provinceCounts'
        ));
    }

    private function resolvePillar(string $role): ?Category
    {
        $val = mb_strtolower(trim(urldecode($role)));
        $slugMap = [
            'تولیدکننده' => 'factories',
            'تولیدکنندگان' => 'factories',
            'factories' => 'factories',
            'واردکننده' => 'importers',
            'واردکنندگان' => 'importers',
            'importers' => 'importers',
            'فروشنده' => 'stores',
            'فروشندگان' => 'stores',
            'فروشندگان-و-تأمین‌کنندگان' => 'stores',
            'stores' => 'stores',
            'چاپخانه' => 'printing-services',
            'چاپخانه‌ها' => 'printing-services',
            'printing-services' => 'printing-services',
            'خدمات‌دهنده' => 'service-providers',
            'خدمات‌دهندگان' => 'service-providers',
            'خدمات' => 'service-providers',
            'service-providers' => 'service-providers',
        ];

        $targetSlug = $slugMap[$val] ?? null;
        if (!$targetSlug) {
            return null;
        }

        return Category::whereNull('parent_id')->where('slug', $targetSlug)->first();
    }

    private function resolveCategoryUnderPillar(Category $pillar, string $category): ?Category
    {
        $raw = urldecode($category);
        if (is_numeric($raw)) {
            $cat = Category::with('parent.parent.parent')->find((int) $raw);
            return ($cat && $cat->rootPillar()->id === $pillar->id) ? $cat : null;
        }

        $all = Category::with('parent.parent.parent')->whereNotNull('parent_id')->get();
        foreach ($all as $cat) {
            if ($cat->rootPillar()->id !== $pillar->id) {
                continue;
            }
            if (slugify($cat->name) === $raw || $cat->slug === $raw) {
                return $cat;
            }
        }

        return null;
    }

    private function resolveProvince(string $province): ?Province
    {
        $raw = urldecode($province);
        if (is_numeric($raw)) {
            return Province::find((int) $raw);
        }

        $all = Province::all();
        foreach ($all as $prov) {
            if (slugify($prov->name) === $raw || $prov->slug === $raw) {
                return $prov;
            }
        }

        return null;
    }

    public function subscriptionFee()
    {
        return view('subscription-fee');
    }

    public function advertising()
    {
        return view('advertising');
    }

    public function sitemap()
    {
        $companies = Company::approved()
            ->with(['category.parent.parent', 'categories.parent.parent'])
            ->get();
        $categories = Category::with('parent.parent.parent')->orderBy('sort')->get();
        $provinces = Province::orderBy('sort')->get();

        return response()
            ->view('sitemap', compact('companies', 'categories', 'provinces'))
            ->header('Content-Type', 'application/xml');
    }

    public function phoneNumbers()
    {
        return view('pages.phone-numbers');
    }

    public function appDownload()
    {
        return view('pages.app');
    }

    public function faqs()
    {
        return view('pages.faqs');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function aboutUs()
    {
        return view('pages.about');
    }

    public function contactUs()
    {
        return view('pages.contact');
    }

    public function submitContact(Request $r)
    {
        $r->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:120',
            'subject' => 'nullable|string|max:150',
            'message' => 'required|string|max:3000',
        ], [
            'name.required' => 'لطفاً نام و نام خانوادگی خود را وارد کنید.',
            'phone.required' => 'لطفاً شماره تماس معتبر وارد فرمایید.',
            'message.required' => 'لطفاً متن پیام خود را بنویسید.',
        ]);

        \Illuminate\Support\Facades\Log::info('Contact Message Received: ', $r->only(['name', 'phone', 'email', 'subject', 'message']));

        return back()->with('ok', 'پیام شما با موفقیت دریافت شد. کارشناسان ۲ کیلو کاغذ در اسرع وقت با شما تماس خواهند گرفت.');
    }

    public function robots()
    {
        $txt = "User-agent: *\nDisallow: /dashboard\nDisallow: /login\nSitemap: " . url('/sitemap.xml') . "\n";
        return response($txt)->header('Content-Type', 'text/plain');
    }
}
