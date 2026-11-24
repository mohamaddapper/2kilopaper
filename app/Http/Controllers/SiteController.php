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
            ->with(['city', 'category', 'province'])
            ->limit(8)
            ->get();
        
        if ($featured->count() < 4) {
            $featured = Company::approved()
                ->ranked()
                ->with(['city', 'category', 'province'])
                ->limit(8)
                ->get();
        }

        // شرکت‌های تخصصی برای تب‌های تفکیکی
        $cartonCompanies = Company::approved()
            ->whereHas('category', fn($q) => $q->where('name', 'like', '%کارتن%')->orWhere('name', 'like', '%ورق%'))
            ->ranked()
            ->with(['city', 'category'])
            ->limit(4)
            ->get();

        $paperCompanies = Company::approved()
            ->whereHas('category', fn($q) => $q->where('name', 'like', '%کاغذ%')->orWhere('name', 'like', '%مقوا%'))
            ->ranked()
            ->with(['city', 'category'])
            ->limit(4)
            ->get();

        $machineryCompanies = Company::approved()
            ->whereHas('category', fn($q) => $q->where('name', 'like', '%ماشین%'))
            ->ranked()
            ->with(['city', 'category'])
            ->limit(4)
            ->get();

        $printCompanies = Company::approved()
            ->whereHas('category', fn($q) => $q->where('name', 'like', '%چاپ%'))
            ->ranked()
            ->with(['city', 'category'])
            ->limit(4)
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
        $q = Company::approved()->with(['city', 'category']);
        if ($s = trim((string) $r->query('q'))) {
            $like = '%' . addcslashes($s, '%_\\') . '%';
            $q->where(fn ($w) => $w->where('name', 'like', $like)->orWhere('description', 'like', $like));
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
        return view('directory', [
            'companies' => $q->ranked()->paginate(12)->withQueryString(),
            'cities' => City::orderBy('name')->get(),
            'provinces' => Province::orderBy('name')->get(),
            'categories' => Category::whereNull('parent_id')->with('children')->orderBy('sort')->get(),
        ]);
    }

    public function company(Company $company)
    {
        abort_unless($company->status === 'approved', 404);
        \App\Services\ViewTracker::track($company, 'company', $company->user_id);
        $company->load(['city', 'category', 'categories' => fn($q) => $q->with('parent')]);
        return view('company', compact('company'));
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
        $companies = Company::approved()->select('slug', 'updated_at')->get();
        return response()->view('sitemap', compact('companies'))->header('Content-Type', 'application/xml');
    }

    public function robots()
    {
        $txt = "User-agent: *\nDisallow: /dashboard\nDisallow: /login\nSitemap: " . url('/sitemap.xml') . "\n";
        return response($txt)->header('Content-Type', 'text/plain');
    }
}
