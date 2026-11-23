<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SiteController extends Controller
{
    public function home()
    {
        $categories = Cache::remember('home.categories', 600, fn () => Category::whereNull('parent_id')->orderBy('sort')->get());
        $featured = Company::approved()->whereNotNull('tier')->ranked()->with(['city', 'category'])->limit(6)->get();
        return view('home', compact('categories', 'featured'));
    }

    public function directory(Request $r)
    {
        $q = Company::approved()->with(['city', 'category']);
        if ($s = trim((string) $r->query('q'))) {
            $like = '%' . addcslashes($s, '%_\\') . '%';
            $q->where(fn ($w) => $w->where('name', 'like', $like)->orWhere('description', 'like', $like));
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
            'categories' => Category::whereNull('parent_id')->with('children')->orderBy('sort')->get(),
        ]);
    }

    public function company(Company $company)
    {
        abort_unless($company->status === 'approved', 404);
        $company->load(['city', 'category', 'categories' => fn($q) => $q->with('parent')]);
        return view('company', compact('company'));
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
