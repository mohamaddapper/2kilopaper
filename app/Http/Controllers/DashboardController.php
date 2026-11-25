<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index(Request $r)
    {
        $user = $r->user();
        $companies = $user ? $user->companies()->latest()->get() : collect();

        $hasJobsTable = false;
        $hasAppsTable = false;
        try {
            $hasJobsTable = \Illuminate\Support\Facades\Schema::hasTable('job_postings');
            $hasAppsTable = \Illuminate\Support\Facades\Schema::hasTable('job_applications');
        } catch (\Throwable $e) {}

        $jobs = ($hasJobsTable && $user)
            ? $user->jobPostings()->with(['company', 'applications'])->latest()->get()
            : collect();

        $totalApplications = ($hasAppsTable && $user)
            ? \App\Models\JobApplication::whereHas('jobPosting', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count()
            : 0;

        $recentApplications = ($hasAppsTable && $user)
            ? \App\Models\JobApplication::whereHas('jobPosting', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->with(['jobPosting.company'])->latest()->take(20)->get()
            : collect();

        $savedCompanies = $user
            ? $user->savedCompanies()->with(['category', 'city', 'province'])->latest('company_bookmarks.created_at')->get()
            : collect();

        $companyIds = $companies->pluck('id')->all();
        $ownerAnalytics = [
            'views_today' => 0,
            'views_7d' => 0,
            'views_30d' => 0,
            'views_total' => (int) $companies->sum('views_count'),
            'phone_clicks' => 0,
            'whatsapp_clicks' => 0,
            'website_clicks' => 0,
            'bookmark_clicks' => 0,
            'series' => ['labels' => [], 'total' => [], 'unique' => []],
            'per_company' => [],
        ];

        if (!empty($companyIds)) {
            $today = now()->toDateString();
            $weekStart = now()->subDays(6)->toDateString();
            $monthStart = now()->subDays(29)->toDateString();

            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('daily_views')) {
                    $ownerAnalytics['views_today'] = (int) \Illuminate\Support\Facades\DB::table('daily_views')
                        ->where('subject_type', 'company')
                        ->whereIn('subject_id', $companyIds)
                        ->where('view_date', $today)
                        ->sum('total_views');

                    $ownerAnalytics['views_7d'] = (int) \Illuminate\Support\Facades\DB::table('daily_views')
                        ->where('subject_type', 'company')
                        ->whereIn('subject_id', $companyIds)
                        ->where('view_date', '>=', $weekStart)
                        ->sum('total_views');

                    $ownerAnalytics['views_30d'] = (int) \Illuminate\Support\Facades\DB::table('daily_views')
                        ->where('subject_type', 'company')
                        ->whereIn('subject_id', $companyIds)
                        ->where('view_date', '>=', $monthStart)
                        ->sum('total_views');

                    $perCompanyViews = \Illuminate\Support\Facades\DB::table('daily_views')
                        ->selectRaw('subject_id, SUM(total_views) as views_30d')
                        ->where('subject_type', 'company')
                        ->whereIn('subject_id', $companyIds)
                        ->where('view_date', '>=', $monthStart)
                        ->groupBy('subject_id')
                        ->pluck('views_30d', 'subject_id');

                    foreach ($companyIds as $cid) {
                        $ownerAnalytics['per_company'][$cid] = [
                            'views_30d' => (int) ($perCompanyViews[$cid] ?? 0),
                            'leads_30d' => 0,
                        ];
                    }
                }

                if (\Illuminate\Support\Facades\Schema::hasTable('company_interactions')) {
                    $leadRow = \Illuminate\Support\Facades\DB::table('company_interactions')
                        ->selectRaw('COALESCE(SUM(phone_clicks),0) as phone, COALESCE(SUM(whatsapp_clicks),0) as whatsapp, COALESCE(SUM(website_clicks),0) as website, COALESCE(SUM(bookmark_clicks),0) as bookmark')
                        ->whereIn('company_id', $companyIds)
                        ->where('stat_date', '>=', $monthStart)
                        ->first();

                    $ownerAnalytics['phone_clicks'] = (int) ($leadRow->phone ?? 0);
                    $ownerAnalytics['whatsapp_clicks'] = (int) ($leadRow->whatsapp ?? 0);
                    $ownerAnalytics['website_clicks'] = (int) ($leadRow->website ?? 0);
                    $ownerAnalytics['bookmark_clicks'] = (int) ($leadRow->bookmark ?? 0);

                    $perCompanyLeads = \Illuminate\Support\Facades\DB::table('company_interactions')
                        ->selectRaw('company_id, SUM(phone_clicks + whatsapp_clicks + website_clicks) as leads_30d')
                        ->whereIn('company_id', $companyIds)
                        ->where('stat_date', '>=', $monthStart)
                        ->groupBy('company_id')
                        ->pluck('leads_30d', 'company_id');

                    foreach ($perCompanyLeads as $cid => $lCount) {
                        if (!isset($ownerAnalytics['per_company'][$cid])) {
                            $ownerAnalytics['per_company'][$cid] = ['views_30d' => 0, 'leads_30d' => 0];
                        }
                        $ownerAnalytics['per_company'][$cid]['leads_30d'] = (int) $lCount;
                    }
                }

                $ownerAnalytics['series'] = \App\Services\ViewTracker::getDailySeries('company', $companyIds, 30);
            } catch (\Throwable $e) {}
        }

        return view('dashboard.index', compact('companies', 'jobs', 'totalApplications', 'recentApplications', 'savedCompanies', 'ownerAnalytics'));
    }

    public function toggleBookmark(Request $r, Company $company)
    {
        $user = $r->user();
        if (!$user) {
            if ($r->expectsJson() || $r->ajax()) {
                return response()->json(['error' => 'unauthenticated', 'redirect' => route('login')], 401);
            }
            return redirect()->route('login');
        }

        $existing = \App\Models\CompanyBookmark::where('user_id', $user->id)
            ->where('company_id', $company->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $saved = false;
            $message = 'شرکت از ذخیره‌ها حذف شد.';
        } else {
            \App\Models\CompanyBookmark::create([
                'user_id' => $user->id,
                'company_id' => $company->id,
            ]);
            \App\Services\ViewTracker::trackInteraction((int) $company->id, 'bookmark');
            $saved = true;
            $message = 'شرکت با موفقیت ذخیره شد.';
        }

        if ($r->expectsJson() || $r->ajax()) {
            return response()->json([
                'saved' => $saved,
                'message' => $message,
                'count' => $company->bookmarks()->count(),
            ]);
        }

        return back()->with('success', $message);
    }

    public function create()
    {
        return view('dashboard.form', $this->formData(new Company));
    }

    public function store(Request $r)
    {
        $data = $this->validated($r);
        $this->processLocations($r, $data);

        if ($r->hasFile('logo')) {
            $data['logo'] = $this->uploadFile($r->file('logo'), 'logos');
        }
        if ($r->hasFile('catalog')) {
            $data['catalog'] = $this->uploadFile($r->file('catalog'), 'catalogs');
        }
        if ($r->hasFile('gallery')) {
            $gallery = [];
            foreach ($r->file('gallery') as $file) {
                if (count($gallery) >= 5) break;
                if ($uploaded = $this->uploadFile($file, 'gallery')) {
                    $gallery[] = $uploaded;
                }
            }
            $data['gallery'] = array_slice($gallery, 0, 5);
        }

        $categoryIds = (array) $r->input('category_ids', []);
        $uniqueIds = array_slice(array_unique(array_filter(array_map('intval', $categoryIds))), 0, 10);
        if (!empty($uniqueIds[0])) {
            $data['category_id'] = $uniqueIds[0];
        }

        $c = new Company($data);
        $c->user_id = $r->user()->id;
        $c->slug = Company::makeSlug($data['name']);
        $c->status = config('gap.auto_approve') ? 'approved' : 'pending';
        $c->save();

        $syncData = [];
        foreach ($uniqueIds as $idx => $catId) {
            $syncData[$catId] = ['is_primary' => ($idx === 0)];
        }
        if (empty($syncData) && !empty($data['category_id'])) {
            $syncData[$data['category_id']] = ['is_primary' => true];
        }
        $c->categories()->sync($syncData);

        return redirect()->route('dashboard')->with('ok', 'شرکت با موفقیت ثبت شد.');
    }

    public function edit(Request $r, Company $company)
    {
        abort_unless($company->user_id === $r->user()->id || $r->user()->isAdmin(), 403);
        return view('dashboard.form', $this->formData($company));
    }

    public function update(Request $r, Company $company)
    {
        abort_unless($company->user_id === $r->user()->id || $r->user()->isAdmin(), 403);
        $data = $this->validated($r);
        $this->processLocations($r, $data);

        if ($r->hasFile('logo')) {
            $data['logo'] = $this->uploadFile($r->file('logo'), 'logos');
        }
        if ($r->hasFile('catalog')) {
            $data['catalog'] = $this->uploadFile($r->file('catalog'), 'catalogs');
        }
        if ($r->hasFile('gallery')) {
            $gallery = $company->gallery ?? [];
            foreach ($r->file('gallery') as $file) {
                if (count($gallery) >= 5) break;
                if ($uploaded = $this->uploadFile($file, 'gallery')) {
                    $gallery[] = $uploaded;
                }
            }
            $data['gallery'] = array_slice($gallery, 0, 5);
        }

        // Admin-only direct controls
        if ($r->user()->isAdmin()) {
            if ($r->has('status') && in_array($r->input('status'), ['pending', 'approved', 'rejected'])) {
                $data['status'] = $r->input('status');
            }
            if ($r->has('tier')) {
                $tier = $r->input('tier');
                if (in_array($tier, ['gold', 'silver', 'bronze'])) {
                    $data['tier'] = $tier;
                    $months = (int) $r->input('tier_months', 12);
                    $data['tier_expires_at'] = now()->addMonths(max(1, $months));
                } else {
                    $data['tier'] = null;
                    $data['tier_expires_at'] = null;
                }
            }
            if ($r->filled('user_phone')) {
                $ownerPhone = normalize_phone((string) $r->input('user_phone'));
                if (preg_match('/^09\d{9}$/', $ownerPhone)) {
                    $owner = \App\Models\User::firstOrCreate(['phone' => $ownerPhone]);
                    $data['user_id'] = $owner->id;
                }
            }
        }

        $categoryIds = (array) $r->input('category_ids', []);
        $uniqueIds = array_slice(array_unique(array_filter(array_map('intval', $categoryIds))), 0, 10);
        if (!empty($uniqueIds[0])) {
            $data['category_id'] = $uniqueIds[0];
        }

        $company->update($data);

        $syncData = [];
        foreach ($uniqueIds as $idx => $catId) {
            $syncData[$catId] = ['is_primary' => ($idx === 0)];
        }
        if (empty($syncData) && !empty($data['category_id'])) {
            $syncData[$data['category_id']] = ['is_primary' => true];
        }
        $company->categories()->sync($syncData);

        $returnTo = (string) ($r->input('return_to') ?: $r->input('redirect_to', ''));
        if ($returnTo !== '') {
            $appHost = parse_url(url('/'), PHP_URL_HOST);
            $retHost = parse_url($returnTo, PHP_URL_HOST);
            $isSafeLocal = (str_starts_with($returnTo, '/') && !str_starts_with($returnTo, '//'))
                || ($retHost && $appHost && strcasecmp($retHost, $appHost) === 0);

            if ($isSafeLocal) {
                $retPath = (string) parse_url($returnTo, PHP_URL_PATH);
                if (str_starts_with(urldecode($retPath), '/company/')) {
                    $company->refresh();
                    $returnTo = $company->url;
                }
                return redirect($returnTo)->with('ok', "اطلاعات شرکت «{$company->name}» با موفقیت ویرایش و ذخیره شد.");
            }
        }

        if ($r->user()->isAdmin()) {
            return redirect()->route('admin.companies')->with('ok', "اطلاعات شرکت «{$company->name}» با موفقیت ویرایش و ذخیره شد.");
        }

        return redirect()->route('dashboard')->with('ok', 'تغییرات با موفقیت ذخیره شد.');
    }

    private function processLocations(Request $r, array &$data): void
    {
        $raw = $r->input('locations');
        if (!is_array($raw) || empty($raw)) {
            return;
        }

        $provinces = Province::all()->keyBy('id');
        $cities = City::all()->keyBy('id');

        $primaryIndex = (int) $r->input('primary_location_index', 0);
        $processed = [];
        $primaryLoc = null;

        foreach ($raw as $idx => $item) {
            $pId = !empty($item['province_id']) ? (int) $item['province_id'] : null;
            $cId = !empty($item['city_id']) ? (int) $item['city_id'] : null;
            if (!$pId || !$cId) {
                continue;
            }

            $province = $provinces->get($pId);
            $city = $cities->get($cId);
            $isPrimary = ($idx === $primaryIndex) || !empty($item['is_primary']);

            $loc = [
                'type' => $item['type'] ?? 'factory',
                'province_id' => $pId,
                'city_id' => $cId,
                'province_name' => $province?->name ?? '',
                'city_name' => $city?->name ?? '',
                'address' => trim((string)($item['address'] ?? '')),
                'phone' => normalize_phone((string)($item['phone'] ?? '')),
                'phone2' => normalize_phone((string)($item['phone2'] ?? '')),
                'is_primary' => $isPrimary,
            ];

            if ($isPrimary && !$primaryLoc) {
                $primaryLoc = $loc;
            }

            $processed[] = $loc;
        }

        if (!$primaryLoc && count($processed) > 0) {
            $processed[0]['is_primary'] = true;
            $primaryLoc = $processed[0];
        }

        if ($primaryLoc) {
            $data['province_id'] = $primaryLoc['province_id'];
            $data['city_id'] = $primaryLoc['city_id'];
            $data['address'] = $primaryLoc['address'];
            if (!empty($primaryLoc['phone']) && empty($data['phone'])) {
                $data['phone'] = $primaryLoc['phone'];
            }
        }

        // Backward compatibility sync for legacy factory/office fields
        foreach ($processed as $loc) {
            if ($loc['type'] === 'factory' && empty($data['factory_address'])) {
                $data['factory_province'] = $loc['province_name'];
                $data['factory_city'] = $loc['city_name'];
                $data['factory_address'] = $loc['address'];
            }
            if (($loc['type'] === 'central_office' || $loc['type'] === 'sales_office') && empty($data['office_address'])) {
                $data['office_province'] = $loc['province_name'];
                $data['office_city'] = $loc['city_name'];
                $data['office_address'] = $loc['address'];
            }
        }

        $data['locations'] = $processed;
    }

    private function uploadFile($file, string $subfolder): ?string
    {
        if (!$file || !$file->isValid()) return null;
        $dir = app()->publicPath() . '/uploads/' . $subfolder;
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $filename);
        return 'uploads/' . $subfolder . '/' . $filename;
    }

    private function formData(Company $company): array
    {
        $provinces = Province::with(['cities' => fn($q) => $q->orderBy('name')])->orderBy('name')->get();
        $locationsTree = $provinces->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'slug' => $p->slug,
            'cities' => $p->cities->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'slug' => $c->slug,
            ])->values(),
        ])->values();

        $pillars = Category::whereNull('parent_id')
            ->with(['children' => fn($q) => $q->with('children')->orderBy('name')])
            ->orderBy('sort')
            ->get();

        $categoriesTree = $pillars->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'slug' => $p->slug,
            'icon' => $p->icon,
            'children' => $p->children->map(fn($ch) => [
                'id' => $ch->id,
                'name' => $ch->name,
                'slug' => $ch->slug,
                'sub_children' => $ch->children->map(fn($sub) => [
                    'id' => $sub->id,
                    'name' => $sub->name,
                    'slug' => $sub->slug,
                ])->values(),
            ])->values(),
        ])->values();

        $initialPillarId = null;
        $selectedCatId = old('category_id', $company->category_id);
        if ($selectedCatId) {
            $cat = Category::with('parent.parent')->find($selectedCatId);
            if ($cat) {
                if (!$cat->parent_id) {
                    $initialPillarId = $cat->id;
                } elseif (!$cat->parent?->parent_id) {
                    $initialPillarId = $cat->parent_id;
                } else {
                    $initialPillarId = $cat->parent->parent_id;
                }
            }
        }

        // Categories flat for quick search and secondary additions
        $allCategories = Category::with('parent.parent')->orderBy('name')->get();
        $categoriesFlat = $allCategories->filter(fn($c) => $c->parent_id !== null)->map(function($c) {
            $curr = $c;
            $path = [$c->name];
            while ($curr->parent) {
                $curr = $curr->parent;
                array_unshift($path, $curr->name);
            }
            return [
                'id' => $c->id,
                'name' => $c->name,
                'slug' => $c->slug,
                'pillar_id' => $curr->id,
                'pillar_name' => $curr->name,
                'pillar_icon' => emoji_to_svg($curr->icon, 14),
                'pillar_slug' => $curr->slug,
                'parent_id' => $c->parent_id,
                'full_path' => implode(' › ', $path),
            ];
        })->values();

        // Selected categories (array of flat category objects)
        $selectedCategories = [];
        $oldCatIds = old('category_ids');
        if (is_array($oldCatIds)) {
            $catIdInts = array_values(array_filter(array_map('intval', $oldCatIds)));
            $selectedCategories = $categoriesFlat->whereIn('id', $catIdInts)->values();
            $order = array_flip($catIdInts);
            $selectedCategories = $selectedCategories->sortBy(fn($item) => $order[$item['id']] ?? 999)->values();
        } elseif ($company->exists) {
            $companyCatIds = $company->categories()->orderByPivot('is_primary', 'desc')->pluck('categories.id')->toArray();
            if (empty($companyCatIds) && $company->category_id) {
                $companyCatIds = [$company->category_id];
            }
            if ($company->category_id && in_array($company->category_id, $companyCatIds)) {
                $companyCatIds = array_values(array_unique(array_merge([$company->category_id], $companyCatIds)));
            }
            $order = array_flip($companyCatIds);
            $selectedCategories = $categoriesFlat->whereIn('id', $companyCatIds)
                ->sortBy(fn($item) => $order[$item['id']] ?? 999)
                ->values();
        }

        return [
            'company' => $company,
            'provinces' => $provinces,
            'locationsTree' => $locationsTree,
            'locationTypes' => Company::locationTypes(),
            'categories' => $pillars,
            'categoriesTree' => $categoriesTree,
            'categoriesFlat' => $categoriesFlat,
            'selectedCategories' => $selectedCategories,
            'initialPillarId' => $initialPillarId,
        ];
    }

    private function validated(Request $r): array
    {
        if (function_exists('fa_to_en_digits')) {
            $r->replace(fa_to_en_digits($r->all()));
        }

        $website = trim((string) $r->input('website'));
        if ($website !== '' && !preg_match('~^https?://~i', $website)) {
            $website = 'https://' . $website;
        }

        $categoryIds = $r->input('category_ids');
        if (is_array($categoryIds) && !empty($categoryIds)) {
            $r->merge(['category_id' => $categoryIds[0]]);
        }

        $r->merge([
            'phone' => normalize_phone((string) $r->input('phone')),
            'website' => $website !== '' ? $website : null,
        ]);

        $validated = $r->validate([
            'name' => 'required|string|max:150',
            'slogan' => 'nullable|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'category_ids' => 'required|array|min:1|max:10',
            'category_ids.*' => 'exists:categories,id',
            'locations' => 'required|array|min:1',
            'locations.*.type' => 'required|string|max:50',
            'locations.*.province_id' => 'required|exists:provinces,id',
            'locations.*.city_id' => 'required|exists:cities,id',
            'locations.*.address' => 'nullable|string|max:1000',
            'locations.*.phone' => 'nullable|string|max:190',
            'locations.*.phone2' => 'nullable|string|max:190',
            'locations.*.is_primary' => 'nullable',
            'primary_location_index' => 'nullable|integer',
            'description' => 'nullable|string|max:10000',
            'phone' => 'nullable|string|max:190',
            'email' => 'nullable|email|max:100',
            'website' => 'nullable|url|max:255',
            'phones' => 'nullable|array',
            'phones.*' => 'nullable|string|max:30',
            'contacts' => 'nullable|array',
            'tech_specs' => 'nullable|array',
            'activity_types' => 'nullable|array',
            'services' => 'nullable|array',
            'video_code' => 'nullable|string|max:500',
            'socials' => 'nullable|array',
            'logo' => 'nullable|image|max:4096',
            'catalog' => 'nullable|mimes:pdf|max:25600',
            'gallery' => 'nullable|array|max:5',
            'gallery.*' => 'nullable|image|max:6144',
        ], [
            'name.required' => 'نام شرکت الزامی است.',
            'category_id.required' => 'حداقل یک دسته‌بندی یا زمینه فعالیت را انتخاب کنید.',
            'category_ids.required' => 'حداقل یک دسته‌بندی یا زمینه فعالیت را انتخاب کنید.',
            'category_ids.min' => 'حداقل یک دسته‌بندی یا زمینه فعالیت را انتخاب کنید.',
            'category_ids.max' => 'حداکثر می‌توانید ۱۰ دسته‌بندی انتخاب نمایید.',
            'locations.required' => 'حداقل وارد کردن یک آدرس یا موقعیت مکانی الزامی است.',
            'locations.min' => 'حداقل وارد کردن یک آدرس یا موقعیت مکانی الزامی است.',
            'locations.*.province_id.required' => 'انتخاب استان برای هر آدرس الزامی است.',
            'locations.*.city_id.required' => 'انتخاب شهر برای هر آدرس الزامی است.',
            'locations.*.address.required' => 'وارد کردن آدرس دقیق برای هر موقعیت الزامی است.',
            'email.email' => 'فرمت آدرس ایمیل وارد شده معتبر نیست.',
            'website.url' => 'آدرس وب‌سایت معتبر نیست (مثال: example.com)',
            'logo.image' => 'فایل لوگو باید تصویر باشد.',
            'logo.max' => 'حجم لوگو نباید بیشتر از ۴ مگابایت باشد.',
            'catalog.mimes' => 'کاتالوگ باید در قالب فایل PDF باشد.',
            'catalog.max' => 'حجم فایل کاتالوگ حداکثر می‌تواند ۲۵ مگابایت باشد.',
            'gallery.max' => 'حداکثر می‌توانید ۵ تصویر برای گالری انتخاب کنید.',
        ]);

        return $validated;
    }
}
