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
        return view('dashboard.index', ['companies' => $r->user()->companies()->latest()->get()]);
    }

    public function create()
    {
        return view('dashboard.form', $this->formData(new Company));
    }

    public function store(Request $r)
    {
        $data = $this->validated($r);

        // Sync location relationships
        if (!empty($data['city_id'])) {
            $city = City::with('province')->find($data['city_id']);
            if ($city) {
                $data['province_id'] = $city->province_id;
                $data['factory_city'] = $city->name;
                $data['factory_province'] = $city->province?->name;
            }
        }
        if (!empty($data['address'])) {
            $data['factory_address'] = $data['address'];
        }
        if (!empty($data['office_city']) && is_numeric($data['office_city'])) {
            $offCity = City::with('province')->find($data['office_city']);
            if ($offCity) {
                $data['office_city'] = $offCity->name;
                $data['office_province'] = $offCity->province?->name;
            }
        }

        if ($r->hasFile('logo')) {
            $data['logo'] = $this->uploadFile($r->file('logo'), 'logos');
        }
        if ($r->hasFile('cover')) {
            $data['cover'] = $this->uploadFile($r->file('cover'), 'covers');
        }
        if ($r->hasFile('catalog')) {
            $data['catalog'] = $this->uploadFile($r->file('catalog'), 'catalogs');
        }
        if ($r->hasFile('gallery')) {
            $gallery = [];
            foreach ($r->file('gallery') as $file) {
                if ($uploaded = $this->uploadFile($file, 'gallery')) {
                    $gallery[] = $uploaded;
                }
            }
            $data['gallery'] = $gallery;
        }

        $c = new Company($data);
        $c->user_id = $r->user()->id;
        $c->slug = Company::makeSlug($data['name']);
        $c->status = config('gap.auto_approve') ? 'approved' : 'pending';
        $c->save();

        return redirect()->route('dashboard')->with('ok', 'شرکت با موفقیت ثبت شد.');
    }

    public function edit(Request $r, Company $company)
    {
        abort_unless($company->user_id === $r->user()->id, 403);
        return view('dashboard.form', $this->formData($company));
    }

    public function update(Request $r, Company $company)
    {
        abort_unless($company->user_id === $r->user()->id, 403);
        $data = $this->validated($r);

        // Sync location relationships
        if (!empty($data['city_id'])) {
            $city = City::with('province')->find($data['city_id']);
            if ($city) {
                $data['province_id'] = $city->province_id;
                $data['factory_city'] = $city->name;
                $data['factory_province'] = $city->province?->name;
            }
        }
        if (!empty($data['address'])) {
            $data['factory_address'] = $data['address'];
        }
        if (!empty($data['office_city']) && is_numeric($data['office_city'])) {
            $offCity = City::with('province')->find($data['office_city']);
            if ($offCity) {
                $data['office_city'] = $offCity->name;
                $data['office_province'] = $offCity->province?->name;
            }
        }

        if ($r->hasFile('logo')) {
            $data['logo'] = $this->uploadFile($r->file('logo'), 'logos');
        }
        if ($r->hasFile('cover')) {
            $data['cover'] = $this->uploadFile($r->file('cover'), 'covers');
        }
        if ($r->hasFile('catalog')) {
            $data['catalog'] = $this->uploadFile($r->file('catalog'), 'catalogs');
        }
        if ($r->hasFile('gallery')) {
            $gallery = $company->gallery ?? [];
            foreach ($r->file('gallery') as $file) {
                if ($uploaded = $this->uploadFile($file, 'gallery')) {
                    $gallery[] = $uploaded;
                }
            }
            $data['gallery'] = $gallery;
        }

        $company->update($data);
        return redirect()->route('dashboard')->with('ok', 'تغییرات با موفقیت ذخیره شد.');
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

        return [
            'company' => $company,
            'provinces' => $provinces,
            'locationsTree' => $locationsTree,
            'categories' => Category::whereNull('parent_id')->with('children')->orderBy('sort')->get(),
        ];
    }

    private function validated(Request $r): array
    {
        $website = trim((string) $r->input('website'));
        if ($website !== '' && !preg_match('~^https?://~i', $website)) {
            $website = 'https://' . $website;
        }

        $r->merge([
            'phone' => normalize_phone((string) $r->input('phone')),
            'website' => $website !== '' ? $website : null,
        ]);

        $validated = $r->validate([
            'name' => 'required|string|max:150',
            'slogan' => 'nullable|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'address' => 'required|string|max:500',
            'description' => 'nullable|string|max:10000',
            'phone' => 'nullable|string|max:30',
            'website' => 'nullable|url|max:255',
            'factory_province' => 'nullable|string|max:100',
            'factory_city' => 'nullable|string|max:100',
            'factory_address' => 'nullable|string|max:1000',
            'office_province' => 'nullable|string|max:100',
            'office_city' => 'nullable|string|max:100',
            'office_address' => 'nullable|string|max:1000',
            'phones' => 'nullable|array',
            'phones.*' => 'nullable|string|max:30',
            'contacts' => 'nullable|array',
            'tech_specs' => 'nullable|array',
            'activity_types' => 'nullable|array',
            'services' => 'nullable|array',
            'video_code' => 'nullable|string|max:500',
            'socials' => 'nullable|array',
            'logo' => 'nullable|image|max:4096',
            'cover' => 'nullable|image|max:8192',
            'catalog' => 'nullable|mimes:pdf|max:25600',
            'gallery.*' => 'nullable|image|max:6144',
        ], [
            'name.required' => 'نام شرکت الزامی است.',
            'category_id.required' => 'انتخاب دسته‌بندی اصلی الزامی است.',
            'province_id.required' => 'انتخاب استان الزامی است.',
            'city_id.required' => 'انتخاب شهر الزامی است.',
            'address.required' => 'وارد کردن آدرس دقیق الزامی است.',
            'website.url' => 'آدرس وب‌سایت معتبر نیست (مثال: example.com)',
            'logo.image' => 'فایل لوگو باید تصویر باشد.',
            'logo.max' => 'حجم لوگو نباید بیشتر از ۴ مگابایت باشد.',
            'cover.image' => 'فایل بنر کاور باید تصویر باشد.',
            'catalog.mimes' => 'کاتالوگ باید در قالب فایل PDF باشد.',
            'catalog.max' => 'حجم فایل کاتالوگ حداکثر می‌تواند ۲۵ مگابایت باشد.',
        ]);

        return $validated;
    }
}
