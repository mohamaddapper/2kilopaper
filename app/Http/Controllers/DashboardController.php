<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use Illuminate\Http\Request;

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
        $c = new Company($data);
        $c->user_id = $r->user()->id;
        $c->slug = Company::makeSlug($data['name']);
        $c->status = config('gap.auto_approve') ? 'approved' : 'pending';
        $c->save();
        return redirect()->route('dashboard')->with('ok', 'شرکت ثبت شد.');
    }

    public function edit(Request $r, Company $company)
    {
        abort_unless($company->user_id === $r->user()->id, 403);
        return view('dashboard.form', $this->formData($company));
    }

    public function update(Request $r, Company $company)
    {
        abort_unless($company->user_id === $r->user()->id, 403);
        $company->update($this->validated($r));
        return redirect()->route('dashboard')->with('ok', 'تغییرات ذخیره شد.');
    }

    private function formData(Company $company): array
    {
        return [
            'company' => $company,
            'cities' => City::orderBy('name')->get(),
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

        return $r->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'city_id' => 'required|exists:cities,id',
            'description' => 'nullable|string|max:3000',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:255',
        ], [
            'name.required' => 'نام شرکت الزامی است.',
            'category_id.required' => 'انتخاب دسته‌بندی الزامی است.',
            'city_id.required' => 'انتخاب شهر الزامی است.',
            'website.url' => 'آدرس وب‌سایت معتبر نیست (مثال: example.com)',
        ]);
    }
}
