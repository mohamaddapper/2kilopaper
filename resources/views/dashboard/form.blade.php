@extends('layouts.app', ['title' => $company->exists ? 'ویرایش شرکت' : 'ثبت شرکت'])

@section('content')
<style>
.location-card {
  background: #fbfcfe;
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 18px;
  margin-bottom: 16px;
  position: relative;
  transition: all 0.2s;
}
.location-card:hover {
  border-color: #cbd5e1;
}
.location-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 14px;
  padding-bottom: 10px;
  border-bottom: 1px dashed var(--border);
}
.btn-remove-loc {
  background: #fee2e2;
  color: #991b1b;
  border: 1px solid #fecaca;
  padding: 4px 12px;
  border-radius: 6px;
  font-size: 12.5px;
  cursor: pointer;
  font-family: inherit;
  font-weight: 600;
  transition: all 0.2s;
}
.btn-remove-loc:hover {
  background: #fca5a5;
}
.cat-search-box {
  position: relative;
  background: #f8fafc;
  border: 1px solid #cbd5e1;
  border-radius: 10px;
  padding: 14px;
  margin-bottom: 16px;
  transition: all 0.2s;
}
.cat-search-box:focus-within {
  border-color: var(--primary);
  background: #fff;
  box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}
.cat-search-dropdown {
  position: absolute;
  top: 100%;
  right: 0;
  left: 0;
  background: #fff;
  border: 1px solid #93c5fd;
  border-radius: 8px;
  box-shadow: 0 10px 25px -5px rgba(0,0,0,0.15), 0 8px 10px -6px rgba(0,0,0,0.1);
  max-height: 320px;
  overflow-y: auto;
  z-index: 200;
  margin-top: 6px;
}
.cat-search-item {
  padding: 10px 14px;
  border-bottom: 1px solid #f1f5f9;
  display: flex;
  justify-content: space-between;
  align-items: center;
  transition: background 0.15s;
  cursor: pointer;
  gap: 10px;
}
.cat-search-item:hover, .cat-search-item.highlighted {
  background: #f0f7ff;
}
.cat-badge-primary {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: #dbeafe;
  color: #1e40af;
  border: 1px solid #bfdbfe;
  padding: 3px 8px;
  border-radius: 12px;
  font-size: 11.5px;
  font-weight: 700;
}
.pillar-filter-pill {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 5px 12px;
  border-radius: 20px;
  border: 1px solid #cbd5e1;
  background: #fff;
  font-size: 12px;
  font-weight: 600;
  color: #475569;
  cursor: pointer;
  transition: all 0.15s ease;
  font-family: inherit;
}
.pillar-filter-pill:hover, .pillar-filter-pill.active {
  background: #eff6ff;
  border-color: #3b82f6;
  color: #1d4ed8;
  box-shadow: 0 1px 3px rgba(59, 130, 246, 0.15);
}
.cat-tag-chip {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  background: #fff;
  border: 1.5px solid #cbd5e1;
  color: #1e293b;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 500;
  transition: all 0.15s ease;
  box-shadow: 0 1px 2px rgba(0,0,0,0.03);
}
.cat-tag-chip:hover {
  border-color: #94a3b8;
  background: #f8fafc;
}
.cat-tag-chip.primary-chip {
  background: #eff6ff;
  border-color: #93c5fd;
  color: #1e40af;
  font-weight: 600;
}
.cat-tag-primary-badge {
  font-size: 10.5px;
  background: #2563eb;
  color: #fff;
  padding: 2px 7px;
  border-radius: 10px;
  font-weight: 700;
}
.cat-set-primary-btn {
  border: 1px solid #cbd5e1;
  background: #f8fafc;
  color: #475569;
  font-size: 10.5px;
  padding: 2px 7px;
  border-radius: 10px;
  cursor: pointer;
  font-family: inherit;
  font-weight: 600;
  transition: all 0.15s;
}
.cat-set-primary-btn:hover {
  background: #dbeafe;
  border-color: #3b82f6;
  color: #1d4ed8;
}
.cat-tag-remove {
  border: none;
  background: none;
  color: #94a3b8;
  cursor: pointer;
  font-size: 13px;
  line-height: 1;
  padding: 0 2px;
  transition: color 0.15s;
  font-family: inherit;
  font-weight: 700;
}
.cat-tag-remove:hover {
  color: #ef4444;
}
.cat-search-item {
  padding: 10px 14px;
  border-bottom: 1px solid #f1f5f9;
  display: flex;
  justify-content: space-between;
  align-items: center;
  transition: background 0.15s;
  cursor: pointer;
  gap: 10px;
}
.cat-search-item:hover, .cat-search-item.highlighted {
  background: #f0f7ff;
}
.cat-search-item:last-child {
  border-bottom: none;
}
.cat-search-item.is-selected {
  background: #f0fdf4;
}
</style>

<div class="page-wrapper" style="margin:20px 0 48px">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:10px">
    <h2 style="margin:0;font-size:22px;color:var(--primary)">{{ $company->exists ? 'ویرایش شرکت: ' . $company->name : 'ثبت نام و اطلاعات شرکت' }}</h2>
    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
      @if(auth()->check() && auth()->user()->isAdmin())
        <a href="{{ route('admin.companies') }}" class="btn ghost small" style="border-color:#0284c7;color:#0284c7;font-weight:700">{!! svg_icon('crown', 15) !!} بازگشت به مدیریت شرکت‌ها</a>
        @if($company->exists)
          <a href="{{ $company->url }}" target="_blank" class="btn ghost small">مشاهده در سایت ↗</a>
        @endif
      @endif
      <a href="{{ route('dashboard') }}" class="btn ghost small">بازگشت به میز کار</a>
    </div>
  </div>

  @if(isset($errors) && $errors->any())
    <div class="notice bad" style="margin-bottom:20px">
      <b style="display:block;margin-bottom:6px">لطفاً موارد زیر را بررسی کنید:</b>
      <ul style="margin:0;padding-right:20px">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  @php
    $rawReturn = old('return_to', request('return_to'));
    $cancelUrl = route('dashboard');
    $appHost = parse_url(url('/'), PHP_URL_HOST);
    if (is_string($rawReturn) && $rawReturn !== '') {
        $retHost = parse_url($rawReturn, PHP_URL_HOST);
        if (str_starts_with($rawReturn, '/') && !str_starts_with($rawReturn, '//')) {
            $cancelUrl = $rawReturn;
        } elseif ($retHost && $appHost && strcasecmp($retHost, $appHost) === 0) {
            $cancelUrl = $rawReturn;
        }
    } elseif (url()->previous() && url()->previous() !== url()->current()) {
        $prev = url()->previous();
        $prevHost = parse_url($prev, PHP_URL_HOST);
        if ($prevHost && $appHost && strcasecmp($prevHost, $appHost) === 0 && !str_contains($prev, '/edit')) {
            $cancelUrl = $prev;
        }
    }
  @endphp

  <form method="post" action="{{ $company->exists ? route('companies.update', $company) : route('companies.store') }}" enctype="multipart/form-data">
    @csrf
    @if($company->exists) @method('PUT') @endif
    <input type="hidden" name="return_to" value="{{ $cancelUrl }}">

    @php
 $f = fn($k) => old($k, $company->$k);
 $cVal = fn($sub, $k) => old("contacts.{$k}", data_get($company->contacts, $k));
 $tVal = fn($sub, $k) => old("tech_specs.{$k}", data_get($company->tech_specs, $k));
 $sVal = fn($sub, $k) => old("socials.{$k}", data_get($company->socials, $k));
 $hasAct = fn($type) => in_array($type, old('activity_types', (array)($company->activity_types ?? [])));
 $hasServ = fn($srv) => in_array($srv, old('services', (array)($company->services ?? [])));
 $rawCerts = old('tech_specs.certificates', data_get($company->tech_specs, 'certificates'));
 $certList = is_array($rawCerts) ? $rawCerts : preg_split('/[;,،]+/u', (string) $rawCerts, -1, PREG_SPLIT_NO_EMPTY);
 $certList = array_values(array_filter(array_map('trim', $certList)));
 $hasCert = fn($cert) => in_array($cert, $certList, true);

 // Initialize location list
 $locList = old('locations', $company->locations);
 if (empty($locList)) {
 if ($company->exists && ($company->address || $company->city_id)) {
 $locList = [
 [
 'type' => 'factory',
 'title' => 'محل کارخانه / دفتر اصلی',
 'province_id' => $company->province_id,
 'city_id' => $company->city_id,
 'address' => $company->address ?: $company->factory_address,
 'phone' => $company->phone,
 'is_primary' => true,
 ]
 ];
 if ($company->office_address || $company->office_city) {
 $locList[] = [
 'type' => 'central_office',
 'title' => 'دفتر مرکزی',
 'province_name' => $company->office_province,
 'city_name' => $company->office_city,
 'address' => $company->office_address,
 'phone' => data_get($company->contacts, 'support_mobile'),
 'is_primary' => false,
 ];
 }
 } else {
 $locList = [
 [
 'type' => 'factory',
 'title' => '',
 'province_id' => '',
 'city_id' => '',
 'address' => '',
 'phone' => '',
 'is_primary' => true,
 ]
 ];
 }
 }
 @endphp

    @php
      $selectedPlan = old('selected_plan', request('plan', ''));
      $isLockedFree = !(auth()->check() && auth()->user()->isAdmin())
          && !$company->hasActiveBenefits()
          && !in_array($selectedPlan, ['3m', '6m', '12m'], true);
      $planTitles = ['3m' => 'اشتراک ۳ ماهه', '6m' => 'اشتراک ۶ ماهه', '12m' => 'اشتراک ۱ ساله'];
    @endphp
    <input type="hidden" name="selected_plan" value="{{ $selectedPlan }}">

    @if(!$company->exists && isset($planTitles[$selectedPlan]))
      <div class="notice ok" style="margin-bottom:18px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px">
        <span>{!! svg_icon('check', 15) !!} پلن انتخابی شما: <strong>{{ $planTitles[$selectedPlan] }}</strong> — تمام امکانات فرم (کاتالوگ، گالری، ویدیو و شبکه‌های اجتماعی) برای شما باز است.</span>
        <a href="{{ route('companies.create') }}" style="font-size:12.5px;font-weight:700;text-decoration:underline">تغییر پلن</a>
      </div>
    @elseif($isLockedFree)
      <div class="notice warn" style="margin-bottom:18px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px">
        <span>در حالت <strong>ثبت رایگان</strong> بخش ۷ (کاتالوگ PDF، گالری تصاویر و ویدیوی معرفی) قفل است. برای فعال‌سازی می‌توانید اشتراک تهیه کنید.</span>
        <a href="{{ $company->exists ? route('dashboard') : route('companies.create') }}" class="btn small" style="background:#d97706;color:#fff;font-size:12px">مشاهده و انتخاب اشتراک</a>
      </div>
    @endif

    @if(auth()->check() && auth()->user()->isAdmin() && $company->exists)
      <!-- بخش ویژه دسترسی مدیریت ارشد سایت -->
      <div class="form-card" style="background:#f0f9ff;border:1.5px solid #0284c7;margin-bottom:24px">
        <h3 style="color:#0369a1;margin-bottom:8px">
          <span>{!! svg_icon('crown', 15) !!} تنظیمات اختصاصی مالک و مدیر ارشد سایت (Admin)</span>
          <span class="badge" style="background:#0284c7;color:#fff">اختیارات ادمین</span>
        </h3>
        <p style="font-size:13px;color:#0369a1;margin:0 0 16px">
          شما با دسترسی مدیریت کل به این شرکت دسترسی دارید. می‌توانید وضعیت، سطح اشتراک و کاربر مالک را مستقیماً مدیریت کنید.
        </p>

        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:16px">
          <div>
            <label style="font-weight:700;font-size:13px;display:block;margin-bottom:6px;color:#0f172a">وضعیت انتشار در دایرکتوری</label>
            <select name="status" style="width:100%;padding:9px;border:1px solid #94a3b8;border-radius:8px;background:#fff;font-family:inherit">
              <option value="approved" @selected(old('status', $company->status) === 'approved')> تأیید و منتشرشده</option>
              <option value="pending" @selected(old('status', $company->status) === 'pending')> در انتظار بررسی</option>
              <option value="rejected" @selected(old('status', $company->status) === 'rejected')> معلق / رد شده</option>
            </select>
          </div>

          <div>
            <label style="font-weight:700;font-size:13px;display:block;margin-bottom:6px;color:#0f172a">وضعیت ویژه تبلیغاتی</label>
            <select name="tier" style="width:100%;padding:9px;border:1px solid #94a3b8;border-radius:8px;background:#fff;font-family:inherit">
              <option value="" @selected(!old('tier', $company->tier))>بدون ویژه (عادی)</option>
              <option value="gold" @selected(old('tier', $company->tier) === 'gold')>ویژه طلایی (اولویت ۱)</option>
              <option value="silver" @selected(old('tier', $company->tier) === 'silver')>ویژه نقره‌ای (اولویت ۲)</option>
              <option value="bronze" @selected(old('tier', $company->tier) === 'bronze')>ویژه برنزی (اولویت ۳)</option>
            </select>
          </div>

          <div>
            <label style="font-weight:700;font-size:13px;display:block;margin-bottom:6px;color:#0f172a">پلن اشتراک شرکت</label>
            <select name="subscription_plan" style="width:100%;padding:9px;border:1px solid #94a3b8;border-radius:8px;background:#fff;font-family:inherit">
              <option value="" @selected(!old('subscription_plan', $company->subscription_plan))>بدون اشتراک</option>
              <option value="12m" @selected(old('subscription_plan', $company->subscription_plan) === '12m')>اشتراک ۱ ساله (اولویت ۴)</option>
              <option value="6m" @selected(old('subscription_plan', $company->subscription_plan) === '6m')>اشتراک ۶ ماهه (اولویت ۵)</option>
              <option value="3m" @selected(old('subscription_plan', $company->subscription_plan) === '3m')>اشتراک ۳ ماهه (اولویت ۶)</option>
            </select>
          </div>

          <div>
            <label style="font-weight:700;font-size:13px;display:block;margin-bottom:6px;color:#0f172a">شماره موبایل کاربر مالک</label>
            <input type="text" name="user_phone" value="{{ old('user_phone', $company->user?->phone) }}" placeholder="مثال: 09121234567" style="width:100%;padding:9px;border:1px solid #94a3b8;border-radius:8px;background:#fff;direction:ltr;text-align:right">
            <span style="display:block;font-size:11.5px;color:#64748b;margin-top:3px">برای واگذاری یا انتقال شرکت به اکانت کاربری مشخص</span>
          </div>
        </div>

        <!-- مدیریت شرکت‌های وابسته (هلدینگ / زیرمجموعه) -->
        <div style="margin-top:18px;padding-top:16px;border-top:1px dashed #7dd3fc">
          <input type="hidden" name="affiliate_ids_present" value="1">
          <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;margin-bottom:8px">
            <label for="affiliate_search_input" style="font-weight:800;font-size:13.5px;color:#0369a1;margin:0;display:flex;align-items:center;gap:6px">
              {!! svg_icon('link', 15) !!} شرکت‌های وابسته و هم‌گروه (هلدینگ / زیرمجموعه‌ها)
            </label>
            <label style="display:inline-flex;align-items:center;gap:6px;font-size:12.5px;color:#0f172a;font-weight:600;cursor:pointer;margin:0">
              <input type="checkbox" name="affiliate_group_sync" value="1" checked>
              <span>همگام‌سازی گروهی (همه اعضای انتخاب‌شده دوطرفه به هم وصل شوند)</span>
            </label>
          </div>
          <p style="margin:0 0 8px;font-size:12px;color:#475569">
            نام شرکت‌های مرتبط را جستجو و انتخاب کنید تا در بخش «شرکت‌های وابسته» پایین صفحهٔ یکدیگر نمایش داده شوند.
          </p>

          <div style="position:relative">
            <input type="text" id="affiliate_search_input" placeholder="جستجوی نام شرکت..." autocomplete="off" style="width:100%;padding:9px 12px;border:1px solid #94a3b8;border-radius:8px;background:#fff;font-family:inherit;font-size:13.5px">
            <div id="affiliate_search_dropdown" style="display:none;position:absolute;top:100%;right:0;left:0;z-index:60;background:#fff;border:1px solid #cbd5e1;border-radius:8px;box-shadow:0 10px 25px rgba(15,23,42,0.12);max-height:240px;overflow-y:auto;margin-top:4px"></div>
          </div>

          <div id="affiliate_tags_wrap" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:10px"></div>
          <div id="affiliate_hidden_inputs"></div>
        </div>

        <script>
          (function() {
            const allAffCompanies = @json($affiliateCompaniesList ?? []);
            let selectedAffs = @json($selectedAffiliates ?? []);
            const inputEl = document.getElementById('affiliate_search_input');
            const ddEl = document.getElementById('affiliate_search_dropdown');
            const tagsEl = document.getElementById('affiliate_tags_wrap');
            const hiddenEl = document.getElementById('affiliate_hidden_inputs');

            function renderAffiliates() {
              if (!tagsEl || !hiddenEl) return;
              tagsEl.innerHTML = '';
              hiddenEl.innerHTML = '';
              selectedAffs.forEach(item => {
                const tag = document.createElement('span');
                tag.style.cssText = 'display:inline-flex;align-items:center;gap:6px;background:#fff;border:1px solid #38bdf8;color:#0369a1;padding:4px 10px;border-radius:99px;font-size:12.5px;font-weight:700';
                tag.innerHTML = `<span>${item.name}${item.city ? ' (' + item.city + ')' : ''}</span><button type="button" style="border:0;background:transparent;color:#dc2626;cursor:pointer;font-size:15px;line-height:1;padding:0 2px" aria-label="حذف">×</button>`;
                tag.querySelector('button').addEventListener('click', () => {
                  selectedAffs = selectedAffs.filter(x => x.id !== item.id);
                  renderAffiliates();
                });
                tagsEl.appendChild(tag);

                const inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = 'affiliate_ids[]';
                inp.value = item.id;
                hiddenEl.appendChild(inp);
              });
            }

            function showDropdown(q) {
              if (!ddEl) return;
              const term = (q || '').trim().toLowerCase();
              if (!term) {
                ddEl.style.display = 'none';
                return;
              }
              const selectedIds = new Set(selectedAffs.map(x => x.id));
              const matches = allAffCompanies
                .filter(c => !selectedIds.has(c.id) && (c.name || '').toLowerCase().includes(term))
                .slice(0, 20);

              if (!matches.length) {
                ddEl.innerHTML = '<div style="padding:10px 12px;font-size:12.5px;color:#64748b">شرکتی با این نام پیدا نشد.</div>';
              } else {
                ddEl.innerHTML = '';
                matches.forEach(c => {
                  const row = document.createElement('div');
                  row.style.cssText = 'padding:8px 12px;cursor:pointer;font-size:13px;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center';
                  row.innerHTML = `<b>${c.name}</b><span style="font-size:11.5px;color:#64748b">${c.city || ''}</span>`;
                  row.addEventListener('mouseenter', () => { row.style.background = '#f0f9ff'; });
                  row.addEventListener('mouseleave', () => { row.style.background = '#fff'; });
                  row.addEventListener('click', () => {
                    selectedAffs.push(c);
                    renderAffiliates();
                    inputEl.value = '';
                    ddEl.style.display = 'none';
                  });
                  ddEl.appendChild(row);
                });
              }
              ddEl.style.display = 'block';
            }

            if (inputEl) {
              inputEl.addEventListener('input', e => showDropdown(e.target.value));
              document.addEventListener('click', e => {
                if (ddEl && !ddEl.contains(e.target) && e.target !== inputEl) {
                  ddEl.style.display = 'none';
                }
              });
            }

            renderAffiliates();
          })();
        </script>
      </div>
    @endif

    <!-- بخش ۱: اطلاعات پایه -->
    <div class="form-card">
      <h3>
        <span>۱. اطلاعات پایه و شناسنامه شرکت</span>
        <span class="badge ok">اصلی</span>
      </h3>
      
      <div class="field">
        <label>نام شرکت یا کسب‌وکار <span style="color:red">*</span></label>
        <input name="name" value="{{ $f('name') }}" placeholder="مثال: ۲ کیلو کاغذ" required>
      </div>

      <div class="form-row" style="margin-top:10px">
        <div class="field">
          <label>لوگوی شرکت (تصویر باکیفیت)</label>
          @if($company->logo)
            <div style="margin-bottom:6px"><img src="{{ asset($company->logo) }}" height="50" style="border-radius:6px;border:1px solid var(--border)"></div>
          @endif
          <input type="file" name="logo" accept="image/png,image/jpeg,image/webp">
          <div class="note">فرمت‌های مجاز: PNG, JPG, WEBP (حداکثر ۱ مگابایت)</div>
        </div>
        <div class="field">
          <label>سال تأسیس</label>
          <input name="tech_specs[established_year]" value="{{ $tVal('tech_specs', 'established_year') }}" placeholder="مثال: 1392">
        </div>
      </div>

      <!-- سامانه یکپارچه انتخاب دسته‌بندی و زمینه فعالیت با جستجو و برچسب (حداکثر ۱۰ مورد) -->
      <div class="cat-search-box" style="margin-top:10px;margin-bottom:6px">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;flex-wrap:wrap;gap:8px">
          <label for="cat_search_input" style="font-weight:700;color:var(--primary);margin:0;font-size:14px;display:flex;align-items:center;gap:6px">
            <span>{!! svg_icon('tag', 15) !!} زمینه‌های فعالیت و دسته‌بندی‌های شرکت <span style="color:red">*</span></span>
          </label>
          <span id="cat_count_badge" class="badge" style="font-size:12px;background:#e2e8f0;color:#334155;font-weight:700">
            ۰ از ۱۰ انتخاب شده
          </span>
        </div>

        <p style="margin:0 0 10px;font-size:12.5px;color:var(--muted)">
          زمینه فعالیت، خدمات یا کالای خود را جستجو کرده یا روی یکی از شاخه‌های پیشنهادی زیر کلیک کنید (امکان انتخاب تا ۱۰ مورد).
        </p>

        <!-- پیشنهاد سریع ستون‌ها/نقش‌های اصلی -->
        <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:10px;align-items:center" id="pillar_pills_container">
          <span style="font-size:12px;color:var(--muted);margin-left:4px">پیشنهاد سریع:</span>
          @foreach($categories as $pillar)
            <button type="button" class="pillar-filter-pill" onclick="filterByPillar({{ $pillar->id }}, '{{ $pillar->name }}')">
              {!! emoji_to_svg($pillar->icon, 16) !!} {{ $pillar->name }}
            </button>
          @endforeach
        </div>

        <!-- کادر جستجو -->
        <div style="position:relative">
          <input type="text" id="cat_search_input" placeholder="تایپ کنید: مثلاً کارتن، جعبه، لاینر، گلاسه، افست، طلاکوب، سلفون، ماشین‌آلات، دایکات..." autocomplete="off" style="padding-right:38px;padding-left:80px;background:#fff;font-size:14px;height:44px;border:1.5px solid #cbd5e1;border-radius:8px;width:100%">
          <span style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:16px;pointer-events:none">{!! svg_icon('search', 15) !!}</span>
          <button type="button" id="cat_search_clear" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);border:none;background:none;cursor:pointer;display:none;color:#ef4444;font-size:12.5px;font-weight:700;font-family:inherit">{!! svg_icon('x', 15) !!} پاک کردن</button>
        </div>

        <!-- لیست نتایج شناور جستجو -->
        <div id="cat_search_results" class="cat-search-dropdown" style="display:none"></div>

        <!-- کانتینر برچسب‌های انتخاب شده -->
        <div style="margin-top:14px;background:#f8fafc;border:1px dashed #cbd5e1;border-radius:10px;padding:14px">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
            <span style="font-size:13px;font-weight:700;color:var(--text)">برچسب‌های انتخاب‌شده شما:</span>
            <button type="button" id="clear_all_cats_btn" onclick="clearAllCategories()" style="display:none;font-size:11.5px;color:#ef4444;border:none;background:none;cursor:pointer;font-family:inherit">{!! svg_icon('x', 15) !!} حذف همه</button>
          </div>

          <div id="selected_tags_container" style="display:flex;gap:8px;flex-wrap:wrap;min-height:36px;align-items:center">
            <!-- توسط جاوااسکریپت پر می‌شود -->
          </div>

          <div id="no_categories_alert" style="font-size:12.5px;color:#e11d48;font-weight:500;padding:6px 0;display:none">
            {!! svg_icon('alert', 15) !!} هنوز هیچ دسته‌بندی یا زمینه فعالیتی انتخاب نشده است. لطفاً حداقل ۱ مورد را با جستجو انتخاب کنید.
          </div>
        </div>
      </div>
    </div>

    <!-- بخش ۲: موقعیت‌های مکانی و آدرس‌ها (سیستم چندگانه پویا: کارخانه، دفتر، فروشگاه، انبار) -->
    <div class="form-card">
      <h3>
        <span>۲. موقعیت‌های مکانی</span>
        <span class="badge ok">امکان ثبت چندگانه</span>
      </h3>
      <p style="margin:0 0 14px;color:var(--muted);font-size:13.5px">
        شما می‌توانید هر تعداد آدرس یا شعبه (کارخانه، دفتر مرکزی، فروشگاه/شوروم، انبار و...) را با مشخصات مجزا ثبت کنید.
      </p>

      <div id="locations_container">
        @foreach($locList as $idx => $loc)
          <div class="location-card" id="loc_card_{{ $idx }}" data-index="{{ $idx }}">
            <div class="location-header">
              <label class="location-primary-label" style="white-space:nowrap;display:inline-flex;align-items:center;gap:6px;cursor:pointer;font-size:13px;color:var(--text);user-select:none;flex-shrink:0">
                <input type="radio" name="primary_location_index" value="{{ $idx }}" @checked(!empty($loc['is_primary']) || $loop->first)>
                <span style="white-space:nowrap"><b>آدرس اصلی</b></span>
              </label>
              <button type="button" class="btn-remove-loc" onclick="removeLocationBlock('{{ $idx }}')" style="{{ count($locList) > 1 ? '' : 'display:none;' }}">
                {!! svg_icon('trash', 15) !!} حذف این آدرس
              </button>
            </div>

            <!-- نوع مکان -->
            <div class="field" style="margin-bottom:12px">
              <label>نوع مکان / ماهیت آدرس <span style="color:red">*</span></label>
              <select name="locations[{{ $idx }}][type]" required>
                @foreach($locationTypes as $typeKey => $typeInfo)
                  <option value="{{ $typeKey }}" @selected(($loc['type'] ?? '') == $typeKey)> {{ $typeInfo['title'] }}</option>
                @endforeach
              </select>
            </div>

            <!-- ردیف استان و شهر هوشمند -->
            <div class="form-row">
              <div class="field" style="flex:1">
                <label>استان <span style="color:red">*</span></label>
                <select name="locations[{{ $idx }}][province_id]" id="loc_prov_{{ $idx }}" required>
                  <option value="">-- انتخاب استان --</option>
                  @foreach($provinces as $pr)
                    <option value="{{ $pr->id }}" data-slug="{{ $pr->slug }}" data-name="{{ $pr->name }}" @selected(($loc['province_id'] ?? '') == $pr->id || ($loc['province_name'] ?? '') == $pr->name)>{{ $pr->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="field fade-in" id="loc_city_wrap_{{ $idx }}" style="flex:1;display:none">
                <label>شهر / شهرستان <span style="color:red">*</span></label>
                <select name="locations[{{ $idx }}][city_id]" id="loc_city_{{ $idx }}" required>
                  <option value="">-- انتخاب شهر --</option>
                </select>
              </div>
            </div>

            <!-- ردیف آدرس دقیق -->
            <div class="field fade-in" id="loc_addr_wrap_{{ $idx }}" style="display:none;margin-top:6px">
              <label>آدرس دقیق پستی <span style="color:red">*</span></label>
              <input name="locations[{{ $idx }}][address]" id="loc_addr_{{ $idx }}" value="{{ $loc['address'] ?? '' }}" placeholder="خیابان، کوچه، پلاک" required>
            </div>

            <!-- تلفن‌های اختصاصی این مکان (۲ فیلد) -->
            <div class="form-row" style="margin-bottom:0;margin-top:10px">
              <div class="field" style="flex:1;margin-bottom:0">
                <label>تلفن مستقیم ۱ این مکان (اختیاری)</label>
                <input name="locations[{{ $idx }}][phone]" value="{{ $loc['phone'] ?? '' }}" placeholder="مثال: 02128421075">
              </div>
              <div class="field" style="flex:1;margin-bottom:0">
                <label>تلفن مستقیم ۲ این مکان (اختیاری)</label>
                <input name="locations[{{ $idx }}][phone2]" value="{{ $loc['phone2'] ?? '' }}" placeholder="مثال: 02128421076">
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <button type="button" class="btn ghost" onclick="addNewLocation()" style="width:100%;margin-top:10px;padding:12px;font-weight:700;border-style:dashed;font-size:14.5px;display:flex;align-items:center;justify-content:center;gap:8px">
        <span>{!! svg_icon('plus', 15) !!} افزودن آدرس و شعبه دیگر (کارخانه دیگر، دفتر، فروشگاه، انبار...)</span>
      </button>
    </div>

    <!-- بخش ۳: محصولات و خدمات تخصصی شرطی -->
    <div class="form-card" id="services_section" style="display:none">
      <h3>
        <span>۳. محصولات و خدمات تخصصی</span>
        <span class="badge" style="background:#eef3f9;color:var(--primary)">هوشمند و تفکیک‌شده</span>
      </h3>

      <!-- ۱. گروه ورق و جعبه کارتن -->
      <div id="group_carton" class="dynamic-box">
        <label style="font-weight:700;display:block;margin-bottom:8px;color:#1e3a8a">{!! svg_icon('box', 15) !!} محصولات و خدمات ورق و جعبه کارتن:</label>
        <div class="check-grid">
          @php
 $cartonItems = [
 'سینگل فیس', 'ورق ۳ لا', 'ورق ۵ لا', 'ورق ۷ لا',
 'فلوت A', 'فلوت B', 'فلوت C', 'فلوت E', 'فلوت F', 'فلوت T',
 'چاپ ۱ رنگ', 'چاپ ۲ رنگ', 'چاپ ۳ رنگ', 'چاپ ۴ رنگ', 'چاپ ۵ رنگ', 'چاپ ۶ رنگ', 'چاپ ۷ رنگ', 'چاپ ۸ رنگ',
 'کارتن معمولی', 'کارتن درب دوبل', 'سینی', 'کارتن دایکاتی', 'کارتن تلسکوپی', 'کارتن لمینتی', 'منقسم'
 ];
 @endphp
          @foreach($cartonItems as $item)
            <label class="check-item">
              <input type="checkbox" name="services[]" value="{{ $item }}" @checked($hasServ($item))>
              <span>{{ $item }}</span>
            </label>
          @endforeach
        </div>
      </div>

      <!-- ۲. گروه خدمات چاپ -->
      <div id="group_printing" class="dynamic-box" style="margin-top:16px">
        <label style="font-weight:700;display:block;margin-bottom:8px;color:#7c2d12">{!! svg_icon('printer', 15) !!} خدمات چاپ:</label>
        <div class="check-grid">
          @php
 $printItems = [
 'فاقد چاپخانه', 'آتلیه طراحی',
 'چاپ افست 1 ورقی', 'چاپ افست 2 ورقی', 'چاپ افست 2/5 ورقی', 'چاپ افست 4/5 ورقی', 'چاپ افست 6 ورقی', 'چاپ افست 9 ورقی',
 'چاپ افست 1 رنگ', 'چاپ افست 2 رنگ', 'چاپ افست 4 رنگ', 'چاپ افست 5 رنگ', 'چاپ افست 6 رنگ', 'چاپ افست 7 رنگ', 'چاپ افست 8 رنگ'
 ];
 @endphp
          @foreach($printItems as $item)
            <label class="check-item">
              <input type="checkbox" name="services[]" value="{{ $item }}" @checked($hasServ($item))>
              <span>{{ $item }}</span>
            </label>
          @endforeach
        </div>
      </div>

      <!-- ۳. گروه خدمات پس از چاپ -->
      <div id="group_post_press" class="dynamic-box" style="margin-top:16px">
        <label style="font-weight:700;display:block;margin-bottom:8px;color:#0e7490">{!! svg_icon('scissors', 15) !!} خدمات پس از چاپ:</label>
        <div class="check-grid">
          @php
 $postPressItems = [
 'یووی شابلونی 4/5 ورقی', 'یووی 2 ورقی', 'یووی 4/5 ورقی', 'یووی 6 ورقی', 'یووی 9 ورقی',
 'دایکات 3 ورقی', 'دایکات 4/5 ورقی', 'دایکات 6 ورقی', 'دایکات 9 ورقی',
 'طلاکوب 2 ورقی', 'طلاکوب 3 ورقی', 'طلاکوب 4/5 ورقی',
 'لترپرس 2 ورقی', 'تاکنی 2 ورقی'
 ];
 @endphp
          @foreach($postPressItems as $item)
            <label class="check-item">
              <input type="checkbox" name="services[]" value="{{ $item }}" @checked($hasServ($item))>
              <span>{{ $item }}</span>
            </label>
          @endforeach
        </div>
      </div>

      <!-- ۴. گروه انواع ماشین‌آلات کارتن‌سازی -->
      <div id="group_carton_machinery" class="dynamic-box" style="margin-top:16px">
        <label style="font-weight:700;display:block;margin-bottom:8px;color:#065f46">{!! svg_icon('settings', 15) !!} انواع ماشین‌آلات کارتن‌سازی:</label>
        <div class="check-grid">
          @php
 $cartonMachineItems = [
 'دستگاه منگنه کارتن', 'دستگاه چاک زن', 'دستگاه تسمه کش', 'دستگاه چاپ فلکسو',
 'دستگاه خط کش برش', 'دستگاه لمینت کارتن', 'دستگاه دایکات روتاری',
 'دستگاه دایکات فکی', 'دستگاه دایکات پینگ پنگی', 'دستگاه لب چسب'
 ];
 @endphp
          @foreach($cartonMachineItems as $item)
            <label class="check-item">
              <input type="checkbox" name="services[]" value="{{ $item }}" @checked($hasServ($item))>
              <span>{{ $item }}</span>
            </label>
          @endforeach
        </div>
      </div>

      <!-- ۵. گروه انواع ماشین‌آلات ورق کارتن -->
      <div id="group_sheet_machinery" class="dynamic-box" style="margin-top:16px">
        <label style="font-weight:700;display:block;margin-bottom:8px;color:#065f46">{!! svg_icon('settings', 15) !!} انواع ماشین‌آلات ورق کارتن:</label>
        <div class="check-grid">
          @php
 $sheetMachineItems = [
 'پری هیتر', 'تری پری هیتر', 'پری کاندیشنر', 'رول گیر کاغذ', 'ترمز سینگل',
 'سینگل فیسر', 'دستگاه چسب زن', 'دابل فیسر', 'اسلیتر اسکورر', 'کاتاف الکترونیکی',
 'دستگاه کاتاف دوبل', 'دستگاه کاتاف هلیکال', 'دستگاه استاکر', 'استاکر دوبل', 'سیلندر کروگیت'
 ];
 @endphp
          @foreach($sheetMachineItems as $item)
            <label class="check-item">
              <input type="checkbox" name="services[]" value="{{ $item }}" @checked($hasServ($item))>
              <span>{{ $item }}</span>
            </label>
          @endforeach
        </div>
      </div>

      <!-- ۶. گروه نصب و خدمات فنی ماشین‌آلات -->
      <div id="group_machine_services" class="dynamic-box" style="margin-top:16px">
        <label style="font-weight:700;display:block;margin-bottom:8px;color:#b45309">{!! svg_icon('wrench', 15) !!} نصب، تعمیرات و خدمات فنی ماشین‌آلات:</label>
        <div class="check-grid">
          @php
 $serviceItems = [
 'نصب و راه‌اندازی خط ورق کارتن', 'نصب و راه‌اندازی خطوط کارتن‌سازی', 'نصب و راه‌اندازی ماشین چاپ و تبدیل',
 'تعمیرات اساسی مکانیک و هیدرولیک', 'اتوماسیون صنعتی و برق PLC', 'سنگ‌زنی سیلندر کروگیت و پرس',
 'تأمین و تراش تیغه‌های اسلیتر و برش', 'قالب‌سازی لیزری و تخت'
 ];
 @endphp
          @foreach($serviceItems as $item)
            <label class="check-item">
              <input type="checkbox" name="services[]" value="{{ $item }}" @checked($hasServ($item))>
              <span>{{ $item }}</span>
            </label>
          @endforeach
        </div>
      </div>
    </div>

    <!-- بخش ۴: اطلاعات تماس و افراد کلیدی -->
    <div class="form-card">
      <h3>
        <span>۴. شماره‌های تماس و افراد کلیدی</span>
        <span class="badge" style="background:#f4f6f9">دسترسی خریداران</span>
      </h3>

      <div class="form-row">
        <div class="field">
          <label>شماره تلفن ثابت اصلی دفتر مرکزی <span style="color:red">*</span></label>
          <input name="phone" value="{{ $f('phone') }}" placeholder="مثال: 02128421075" required>
        </div>
        <div class="field">
          <label>شماره موبایل پشتیبانی / پیام‌رسان‌ها</label>
          <input name="contacts[support_mobile]" value="{{ $cVal('contacts', 'support_mobile') }}" placeholder="مثال: 09121234567">
        </div>
      </div>

      <div style="margin-top:14px;background:#fbfcfe;border:1px solid var(--border);border-radius:10px;padding:16px">
        <h4 style="margin:0 0 12px;font-size:14.5px;color:var(--text)">{!! svg_icon('users', 15) !!} مشخصات مدیران و مسئولین شرکت:</h4>
        
        <div class="field" style="margin-bottom:12px">
          <label>نام و نام خانوادگی مالک شرکت / کسب‌وکار</label>
          <input name="contacts[owner_name]" value="{{ $cVal('contacts', 'owner_name') }}">
        </div>

        <div class="form-row" style="margin-bottom:12px">
          <div class="field" style="margin-bottom:0">
            <label>نام و نام خانوادگی مدیرعامل</label>
            <input name="contacts[ceo_name]" value="{{ $cVal('contacts', 'ceo_name') }}">
          </div>
          <div class="field" style="margin-bottom:0">
            <label>شماره تماس مستقیم مدیرعامل</label>
            <input name="contacts[ceo_phone]" value="{{ $cVal('contacts', 'ceo_phone') }}" placeholder="09121234567" dir="ltr" style="text-align:right;font-family:ui-sans-serif,system-ui,-apple-system,sans-serif">
          </div>
        </div>

        <div class="form-row" style="margin-bottom:12px">
          <div class="field" style="margin-bottom:0">
            <label>نام و نام خانوادگی مدیر فروش</label>
            <input name="contacts[sales_name]" value="{{ $cVal('contacts', 'sales_name') }}">
          </div>
          <div class="field" style="margin-bottom:0">
            <label>شماره تماس مستقیم مدیر فروش</label>
            <input name="contacts[sales_phone]" value="{{ $cVal('contacts', 'sales_phone') }}" placeholder="09121234567" dir="ltr" style="text-align:right;font-family:ui-sans-serif,system-ui,-apple-system,sans-serif">
          </div>
        </div>

        <div class="form-row">
          <div class="field" style="margin-bottom:0">
            <label>نام مسئول خرید / تدارکات</label>
            <input name="contacts[purchase_name]" value="{{ $cVal('contacts', 'purchase_name') }}">
          </div>
          <div class="field" style="margin-bottom:0">
            <label>شماره تماس مسئول خرید</label>
            <input name="contacts[purchase_phone]" value="{{ $cVal('contacts', 'purchase_phone') }}" placeholder="09121234567" dir="ltr" style="text-align:right;font-family:ui-sans-serif,system-ui,-apple-system,sans-serif">
          </div>
        </div>
      </div>
    </div>

    <!-- بخش ۵: مشخصات فنی و توان تولید -->
    <div class="form-card" id="tech_specs_section" style="display:none">
      <h3>
        <span id="tech_specs_title">۵. توانمندی‌های فنی و تولیدی</span>
        <span id="tech_specs_badge" class="badge" style="background:#f4f6f9">صنعتی</span>
      </h3>

      <div id="tech_specs_fields_grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px">
        <div class="field" id="field_moq">
          <label>حداقل تیراژ سفارش (MOQ)</label>
          <input name="tech_specs[moq]" value="{{ $tVal('tech_specs', 'moq') }}" placeholder="مثال: ۵۰۰۰ عدد یا ۱۰ تن">
        </div>
        <div class="field" id="field_capacity">
          <label id="label_capacity">ظرفیت تولید ماهانه</label>
          <input name="tech_specs[capacity]" value="{{ $tVal('tech_specs', 'capacity') }}" placeholder="مثال: ۵۰۰ تن در ماه">
        </div>
        <div class="field" id="field_production_width">
          <label>عرض تولید دستگاه‌ها</label>
          <input name="tech_specs[production_width]" value="{{ $tVal('tech_specs', 'production_width') }}" placeholder="مثال: ۲۲۰ سانتی‌متر">
        </div>
        <div class="field" id="field_gsm_range">
          <label id="label_gsm_range">محدوده گراماژ تولید</label>
          <input name="tech_specs[gsm_range]" value="{{ $tVal('tech_specs', 'gsm_range') }}" placeholder="مثال: ۱۱۰ تا ۴۰۰ گرم">
        </div>
        <div class="field" id="field_custom_order">
          <label id="label_custom_order">امکان تولید اختصاصی / سفارشی</label>
          <select name="tech_specs[custom_order]">
            <option value="" @selected($tVal('tech_specs', 'custom_order') === '')>انتخاب کنید...</option>
            <option value="بله" @selected($tVal('tech_specs', 'custom_order') == 'بله')>بله، پذیرفته می‌شود</option>
            <option value="خیر" @selected($tVal('tech_specs', 'custom_order') == 'خیر')>خیر</option>
          </select>
        </div>
      </div>

      <div class="field" id="field_certificates" style="margin-top:10px;margin-bottom:0">
        <label>گواهینامه‌های کیفی و استانداردها:</label>
        <div class="check-grid">
          @php
          $certs = [
              'ISO 45001:2018',
              'ISO 45001:2015',
              'ISO 18001:2007',
              'ISO 14001:2015',
              'ISO 14001:2026',
              'ISO 10004:2012',
              'ISO 10002:2018',
              'ISO 10002:2014',
              'ISO 9001:2015',
              'ISO 9001:2008',
              'IMS',
              'HSE',
              'نشان استاندارد',
              'سیب سلامت',
          ];
          $certs = array_values(array_unique(array_merge($certs, $certList)));
          @endphp
          @foreach($certs as $cert)
            @php $isLatinCert = (bool) preg_match('/[A-Za-z0-9]/', $cert); @endphp
            <label class="check-item">
              <input type="checkbox" name="tech_specs[certificates][]" value="{{ $cert }}" @checked($hasCert($cert))>
              <span @if($isLatinCert) dir="ltr" style="font-family:ui-sans-serif,system-ui,-apple-system,sans-serif;direction:ltr;unicode-bidi:isolate" @endif>{{ $cert }}</span>
            </label>
          @endforeach
        </div>
      </div>
    </div>

    <!-- بخش ۶: شرح کالا و خدمات -->
    <div class="form-card">
      <h3>
        <span>۶. شرح کالا و خدمات شرکت <span style="color:red">*</span></span>
      </h3>
      <div class="field" style="margin-bottom:0">
        <label>متن معرفی کامل، تاریخچه، توانایی‌ها و محصولات شرکت</label>
        <textarea name="description" rows="6" placeholder="درباره شرکت خود، خطوط تولید و مزیت‌های رقابتی توضیح دهید..." required>{{ $f('description') }}</textarea>
        <div class="note">این توضیحات در سئو و رتبه صفحه شما در موتورهای جستجو تأثیر مستقیم دارد.</div>
      </div>
    </div>

    <!-- بخش ۷: امکانات اختصاصی اشتراک -->
    <div class="form-card">
      <h3>
        <span>۷. امکانات اختصاصی اشتراک (گالری تصاویر، کاتالوگ PDF و ویدیو)</span>
        <span class="premium-tag">مخصوص اشتراک</span>
      </h3>

      @if($isLockedFree)
        <div style="margin-bottom:14px;padding:10px 14px;border-radius:8px;background:#fffbeb;border:1px solid #fde68a;color:#92400e;font-size:12.5px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px">
          <span>تمامی فیلدهای این بخش (۵ عکس محصولات، کاتالوگ PDF و ویدیوی معرفی) با تهیه اشتراک ۳ ماهه، ۶ ماهه یا ۱ ساله فعال می‌شوند.</span>
          <a href="{{ $company->exists ? route('dashboard') : route('companies.create') }}" style="font-weight:700;color:#b45309">تهیه اشتراک و فعال‌سازی ←</a>
        </div>
      @endif

      <fieldset @disabled($isLockedFree) style="border:0;margin:0;padding:0;min-width:0;{{ $isLockedFree ? 'opacity:0.55;pointer-events:none;' : '' }}">
        <div class="form-row">
          <div class="field">
            <label>{!! svg_icon('camera', 15) !!} گالری تصاویر محصولات یا کارخانه (حداکثر ۵ تصویر)</label>
            @if($company->gallery)
              <div style="display:flex;gap:6px;margin-bottom:6px;flex-wrap:wrap">
                @foreach((array)$company->gallery as $img)
                  <img src="{{ asset($img) }}" height="40" style="border-radius:4px;border:1px solid var(--border)">
                @endforeach
              </div>
            @endif
            <input type="file" name="gallery[]" id="gallery_input" multiple accept="image/png,image/jpeg,image/webp">
            <div class="note">می‌توانید هم‌زمان حداکثر ۵ عکس با فرمت‌های PNG, JPG, WEBP انتخاب کنید.</div>
          </div>

          <div class="field">
            <label>{!! svg_icon('file-text', 15) !!} کاتالوگ محصولات و خدمات (فایل PDF)</label>
            @if($company->catalog)
              <div style="margin-bottom:6px"><a href="{{ asset($company->catalog) }}" target="_blank" style="font-weight:700">مشاهده کاتالوگ فعلی</a></div>
            @endif
            <input type="file" name="catalog" accept="application/pdf">
            <div class="note">فرمت مجاز: PDF (حداکثر ۵ مگابایت · فقط ۱ فایل)</div>
          </div>
        </div>

        <div class="field" style="margin-top:10px;margin-bottom:0">
          <label>{!! svg_icon('video', 15) !!} کد ویدیوی آپارات یا لینک ویدیو</label>
          <input name="video_code" value="{{ $f('video_code') }}" placeholder="مثال: کد یا لینک اشتراک‌گذاری آپارات">
          <div class="note">در صورت وارد کردن، پلیر ویدیوی معرفی کارخانه در صفحه شرکت نمایش داده می‌شود.</div>
        </div>
      </fieldset>
    </div>

    <!-- بخش ۸: وب‌سایت، ایمیل و شبکه‌های اجتماعی (آزاد و رایگان) -->
    <div class="form-card">
      <h3>
        <span>۸. وب‌سایت، ایمیل و شبکه‌های اجتماعی</span>
      </h3>

      <div class="form-row">
        <div class="field">
          <label>آدرس وب سایت</label>
          <input name="website" value="{{ $f('website') }}" placeholder="2kilopaper.ir" dir="ltr" style="text-align:left;font-family:ui-sans-serif,system-ui,-apple-system,sans-serif">
        </div>
        <div class="field">
          <label>آدرس ایمیل</label>
          <input type="email" name="email" id="email_input" value="{{ $f('email') }}" placeholder="info@company.ir" dir="ltr" style="text-align:left;font-family:ui-sans-serif,system-ui,-apple-system,sans-serif;transition:all 0.2s ease;">
          <div id="email_feedback" style="display:none;font-size:12px;margin-top:5px;font-weight:500;"></div>
        </div>
      </div>

      <div class="form-row" style="margin-top:10px">
        <div class="field">
          <label>شماره واتساپ</label>
          <input name="socials[whatsapp]" value="{{ $sVal('socials', 'whatsapp') }}" placeholder="09123456789" dir="ltr" style="text-align:left;font-family:ui-sans-serif,system-ui,-apple-system,sans-serif">
        </div>
        <div class="field">
          <label>آیدی اینستاگرام</label>
          <input name="socials[instagram]" value="{{ $sVal('socials', 'instagram') }}" placeholder="2kilopaper" dir="ltr" style="text-align:left;font-family:ui-sans-serif,system-ui,-apple-system,sans-serif">
        </div>
        <div class="field">
          <label>کانال یا آیدی تلگرام</label>
          <input name="socials[telegram]" value="{{ $sVal('socials', 'telegram') }}" placeholder="2kilopaper" dir="ltr" style="text-align:left;font-family:ui-sans-serif,system-ui,-apple-system,sans-serif">
        </div>
      </div>
    </div>

    <div style="display:flex;gap:12px;align-items:center;margin-top:20px">
      <button type="submit" class="btn" style="padding:12px 32px;font-size:16px;font-weight:700">
        {{ $company->exists ? 'ذخیره تغییرات شرکت' : 'ثبت نهایی اطلاعات شرکت' }}
      </button>
      <a href="{{ $cancelUrl }}" class="btn ghost">انصراف</a>
    </div>
  </form>
</div>

<script>
const locationsTree = @json($locationsTree);
const locationTypesData = @json($locationTypes);
const categoriesFlat = @json($categoriesFlat);
let selectedCategories = @json($selectedCategories);
let nextLocIndex = {{ count($locList) }};

function renderCategoryTags() {
 const container = document.getElementById('selected_tags_container');
 const countBadge = document.getElementById('cat_count_badge');
 const noAlert = document.getElementById('no_categories_alert');
 const clearAllBtn = document.getElementById('clear_all_cats_btn');

 if (!container) return;
 container.innerHTML = '';

 const total = selectedCategories.length;
 if (countBadge) {
 countBadge.textContent = `${total} از ۱۰ انتخاب شده`;
 if (total >= 10) {
 countBadge.style.background = '#fef2f2';
 countBadge.style.color = '#b91c1c';
 } else if (total > 0) {
 countBadge.style.background = '#eff6ff';
 countBadge.style.color = '#1d4ed8';
 } else {
 countBadge.style.background = '#e2e8f0';
 countBadge.style.color = '#334155';
 }
 }

 if (total === 0) {
 if (noAlert) noAlert.style.display = 'block';
 if (clearAllBtn) clearAllBtn.style.display = 'none';
 return;
 }

 if (noAlert) noAlert.style.display = 'none';
 if (clearAllBtn) clearAllBtn.style.display = 'inline-block';

  selectedCategories.forEach((cat, index) => {
    const isPrimary = (index === 0);
    const chip = document.createElement('div');
    chip.className = `cat-tag-chip fade-in ${isPrimary ? 'primary-chip' : ''}`;
    chip.id = `cat_chip_${cat.id}`;
    chip.innerHTML = `
      <span style="display:inline-flex;align-items:center">${cat.pillar_icon || ''}</span>
      <span style="color:#64748b;font-size:12px">${cat.pillar_name}:</span>
      <span>${cat.name}</span>
      ${isPrimary
        ? '<span class="cat-tag-primary-badge">شاخه اصلی</span>'
        : `<button type="button" class="cat-set-primary-btn" onclick="setPrimaryCategory(${cat.id})" title="تنظیم به عنوان شاخه اصلی">انتخاب به عنوان اصلی</button>`}
      <input type="hidden" name="category_ids[]" value="${cat.id}">
      <button type="button" class="cat-tag-remove" onclick="removeCategory(${cat.id})" title="حذف این دسته">&times;</button>
    `;
    container.appendChild(chip);
  });
}

function setPrimaryCategory(catId) {
  const id = parseInt(catId, 10);
  const idx = selectedCategories.findIndex(c => c.id === id);
  if (idx <= 0) return;
  const [picked] = selectedCategories.splice(idx, 1);
  selectedCategories.unshift(picked);
  renderCategoryTags();
}

function addCategory(catId) {
 const id = parseInt(catId, 10);
 if (!id) return;

 if (selectedCategories.some(c => c.id === id)) {
 const existing = selectedCategories.find(c => c.id === id);
 alert(`«${existing ? existing.name : ''}» قبلاً انتخاب شده است.`);
 return;
 }

 if (selectedCategories.length >= 10) {
 alert('حداکثر می‌توانید ۱۰ دسته‌بندی انتخاب نمایید.');
 return;
 }

 const cat = categoriesFlat.find(c => c.id === id);
 if (!cat) return;

 selectedCategories.push(cat);
 renderCategoryTags();
 toggleServices();
 updateSearchDropdownSelection();
}

function removeCategory(catId) {
 const id = parseInt(catId, 10);
 selectedCategories = selectedCategories.filter(c => c.id !== id);
 renderCategoryTags();
 toggleServices();
 updateSearchDropdownSelection();
}

function clearAllCategories() {
 if (selectedCategories.length === 0) return;
 if (confirm('آیا از حذف تمام برچسب‌های انتخاب‌شده اطمینان دارید؟')) {
 selectedCategories = [];
 renderCategoryTags();
 toggleServices();
 updateSearchDropdownSelection();
 }
}

function updateSearchDropdownSelection() {
 const resultsBox = document.getElementById('cat_search_results');
 if (!resultsBox || resultsBox.style.display === 'none') return;

 const items = resultsBox.querySelectorAll('.cat-search-item');
 items.forEach(el => {
 const catId = parseInt(el.dataset.id, 10);
 const isSelected = selectedCategories.some(c => c.id === catId);
 const actionSlot = el.querySelector('.action-slot');
 if (isSelected) {
 el.classList.add('is-selected');
 if (actionSlot) {
 actionSlot.innerHTML = `<span style="font-size:12px;color:#16a34a;font-weight:700"> انتخاب شده</span>`;
 }
 } else {
 el.classList.remove('is-selected');
 if (actionSlot) {
 actionSlot.innerHTML = `<span style="font-size:12px;color:#2563eb;font-weight:600"> انتخاب</span>`;
 }
 }
 });
}

function filterByPillar(pillarId, pillarName = '') {
 const input = document.getElementById('cat_search_input');
 const resultsBox = document.getElementById('cat_search_results');
 const clearBtn = document.getElementById('cat_search_clear');
 if (!resultsBox) return;

 if (input) input.value = '';
 if (clearBtn) clearBtn.style.display = 'block';

 // Toggle active pill state
 document.querySelectorAll('.pillar-filter-pill').forEach(btn => {
 btn.classList.toggle('active', btn.getAttribute('onclick')?.includes(String(pillarId)));
 });

 const matches = categoriesFlat.filter(item => item.pillar_id == pillarId);

 let html = `
 <div style="padding:10px 14px;background:#f8fafc;border-bottom:1px solid #e2e8f0;font-size:12.5px;color:#475569;font-weight:700">
 دسته‌های مرتبط با: ${pillarName || 'شاخه انتخابی'} (${matches.length} مورد)
 </div>
 `;

 matches.forEach(item => {
 const isSelected = selectedCategories.some(c => c.id === item.id);
 html += `
 <div class="cat-search-item ${isSelected ? 'is-selected' : ''}" data-id="${item.id}" onclick="handleSearchItemClick(${item.id})">
 <div style="flex:1">
 <div style="font-weight:700;font-size:13.5px;color:var(--text);display:flex;align-items:center;gap:6px">
 <span class="cat-badge-primary">${item.pillar_icon || ''} ${item.pillar_name}</span>
 <span>${item.name}</span>
 </div>
 <div style="font-size:11.5px;color:var(--muted);margin-top:3px">${item.full_path}</div>
 </div>
 <div class="action-slot" style="margin-right:10px">
 ${isSelected 
 ? '<span style="font-size:12px;color:#16a34a;font-weight:700"> انتخاب شده</span>' 
 : '<span style="font-size:12px;color:#2563eb;font-weight:600"> انتخاب</span>'}
 </div>
 </div>
 `;
 });

 resultsBox.innerHTML = html;
 resultsBox.style.display = 'block';
}

function handleSearchItemClick(catId) {
 const isSelected = selectedCategories.some(c => c.id === catId);
 if (isSelected) {
 removeCategory(catId);
 } else {
 addCategory(catId);
 }
}

function initCategorySearch() {
 const input = document.getElementById('cat_search_input');
 const resultsBox = document.getElementById('cat_search_results');
 const clearBtn = document.getElementById('cat_search_clear');

 if (!input || !resultsBox) return;

 function highlight(text, q) {
 if (!q) return text;
 const re = new RegExp('(' + q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
 return text.replace(re, '<mark style="background:#fef08a;color:#854d0e;padding:0 2px;border-radius:2px">$1</mark>');
 }

 function doSearch(term) {
 document.querySelectorAll('.pillar-filter-pill').forEach(btn => btn.classList.remove('active'));

 const q = term.trim().toLowerCase();
 if (!q) {
 resultsBox.style.display = 'none';
 resultsBox.innerHTML = '';
 if (clearBtn) clearBtn.style.display = 'none';
 return;
 }
 if (clearBtn) clearBtn.style.display = 'block';

 const norm = s => s.replace(/ي/g, 'ی').replace(/ك/g, 'ک');
 const nq = norm(q);

 const matches = categoriesFlat.filter(item => {
 const name = norm(item.name.toLowerCase());
 const path = norm(item.full_path.toLowerCase());
 return name.includes(nq) || path.includes(nq);
 }).slice(0, 25);

 if (matches.length === 0) {
 resultsBox.innerHTML = `
 <div style="padding:16px;text-align:center;color:var(--muted);font-size:13px">
 موردی با عنوان «<b>${term}</b>» یافت نشد. می‌توانید با کلمات دیگر جستجو کنید یا از دکمه‌های پیشنهاد سریع بالا استفاده نمایید.
 </div>
 `;
 resultsBox.style.display = 'block';
 return;
 }

 let html = `
 <div style="padding:8px 14px;background:#f8fafc;border-bottom:1px solid #e2e8f0;font-size:12px;color:var(--muted)">
 برای افزودن یا حذف از برچسب‌های شرکت، روی گزینه مورد نظر کلیک کنید:
 </div>
 `;

 matches.forEach(item => {
 const isSelected = selectedCategories.some(c => c.id === item.id);
 html += `
 <div class="cat-search-item ${isSelected ? 'is-selected' : ''}" data-id="${item.id}" onclick="handleSearchItemClick(${item.id})">
 <div style="flex:1">
 <div style="font-weight:700;font-size:13.5px;color:var(--text);display:flex;align-items:center;gap:6px">
 <span class="cat-badge-primary">${item.pillar_icon || ''} ${item.pillar_name}</span>
 <span>${highlight(item.name, q)}</span>
 </div>
 <div style="font-size:11.5px;color:var(--muted);margin-top:3px">${highlight(item.full_path, q)}</div>
 </div>
 <div class="action-slot" style="margin-right:10px">
 ${isSelected 
 ? '<span style="font-size:12px;color:#16a34a;font-weight:700"> انتخاب شده</span>' 
 : '<span style="font-size:12px;color:#2563eb;font-weight:600"> انتخاب</span>'}
 </div>
 </div>
 `;
 });

 resultsBox.innerHTML = html;
 resultsBox.style.display = 'block';
 }

 input.addEventListener('input', function() {
 doSearch(this.value);
 });

 input.addEventListener('focus', function() {
 if (this.value.trim()) doSearch(this.value);
 });

 if (clearBtn) {
 clearBtn.addEventListener('click', function() {
 input.value = '';
 doSearch('');
 document.querySelectorAll('.pillar-filter-pill').forEach(btn => btn.classList.remove('active'));
 input.focus();
 });
 }

 document.addEventListener('click', function(e) {
 if (!input.contains(e.target) && !resultsBox.contains(e.target) && !e.target.closest('#pillar_pills_container')) {
 resultsBox.style.display = 'none';
 }
 });
}

function initLocationCascade(idx, initialProvinceVal = '', initialCityVal = '') {
 const provEl = document.getElementById('loc_prov_' + idx);
 const cityWrap = document.getElementById('loc_city_wrap_' + idx);
 const cityEl = document.getElementById('loc_city_' + idx);
 const addrWrap = document.getElementById('loc_addr_wrap_' + idx);
 const addrEl = document.getElementById('loc_addr_' + idx);

 if (!provEl || !cityWrap || !cityEl) return;

 function findProvince(val) {
 if (!val) return null;
 return locationsTree.find(p => p.id == val || p.name === val || p.slug === val);
 }

 function renderCities(prov, selectedVal = '') {
 cityEl.innerHTML = '<option value="">-- انتخاب شهر --</option>';
 if (!prov || !prov.cities) return;

 prov.cities.forEach(c => {
 const opt = document.createElement('option');
 opt.value = c.id;
 opt.textContent = c.name;
 opt.dataset.slug = c.slug;
 opt.dataset.name = c.name;
 if (selectedVal && (c.id == selectedVal || c.name === selectedVal || c.slug === selectedVal)) {
 opt.selected = true;
 }
 cityEl.appendChild(opt);
 });
 }

 provEl.addEventListener('change', function() {
 const prov = findProvince(this.value);
 if (prov) {
 renderCities(prov);
 cityWrap.style.display = 'block';
 if (addrWrap) addrWrap.style.display = 'none';
 } else {
 cityWrap.style.display = 'none';
 if (addrWrap) addrWrap.style.display = 'none';
 cityEl.innerHTML = '<option value="">-- انتخاب شهر --</option>';
 }
 });

 cityEl.addEventListener('change', function() {
 if (this.value) {
 if (addrWrap) {
 addrWrap.style.display = 'block';
 if (addrEl && !addrEl.value) {
 addrEl.focus();
 }
 }
 } else {
 if (addrWrap) addrWrap.style.display = 'none';
 }
 });

 // Initial restore
 if (initialProvinceVal) {
 const prov = findProvince(initialProvinceVal);
 if (prov) {
 provEl.value = prov.id;
 renderCities(prov, initialCityVal);
 cityWrap.style.display = 'block';
 if (initialCityVal && addrWrap) {
 addrWrap.style.display = 'block';
 }
 }
 }
}

function updateLocationNumbers() {
 const cards = document.querySelectorAll('#locations_container .location-card');
 cards.forEach((card) => {
 const delBtn = card.querySelector('.btn-remove-loc');
 if (delBtn) delBtn.style.display = cards.length > 1 ? 'inline-block' : 'none';
 });
}

function removeLocationBlock(idx) {
 const cards = document.querySelectorAll('#locations_container .location-card');
 if (cards.length <= 1) {
 alert('حداقل وارد کردن یک موقعیت مکانی الزامی است.');
 return;
 }
 const target = document.getElementById('loc_card_' + idx);
 if (target) {
 target.remove();
 updateLocationNumbers();

 // Ensure one primary radio is checked
 const checked = document.querySelector('input[name="primary_location_index"]:checked');
 if (!checked) {
 const firstRadio = document.querySelector('input[name="primary_location_index"]');
 if (firstRadio) firstRadio.checked = true;
 }
 }
}

function addNewLocation() {
 const idx = nextLocIndex++;
 const container = document.getElementById('locations_container');

 let typeOptions = '';
 for (const [key, val] of Object.entries(locationTypesData)) {
 typeOptions += `<option value="${key}">${val.icon} ${val.title}</option>`;
 }

 let provOptions = '<option value="">-- انتخاب استان --</option>';
 locationsTree.forEach(p => {
 provOptions += `<option value="${p.id}" data-name="${p.name}">${p.name}</option>`;
 });

 const cardHtml = `
 <div class="location-card fade-in" id="loc_card_${idx}" data-index="${idx}">
 <div class="location-header">
 <label class="location-primary-label" style="white-space:nowrap;display:inline-flex;align-items:center;gap:6px;cursor:pointer;font-size:13px;color:var(--text);user-select:none;flex-shrink:0">
 <input type="radio" name="primary_location_index" value="${idx}">
 <span style="white-space:nowrap"><b>آدرس اصلی</b></span>
 </label>
 <button type="button" class="btn-remove-loc" onclick="removeLocationBlock('${idx}')">
 حذف این آدرس
 </button>
 </div>

 <div class="field" style="margin-bottom:12px">
 <label>نوع مکان / ماهیت آدرس <span style="color:red">*</span></label>
 <select name="locations[${idx}][type]" required>
 ${typeOptions}
 </select>
 </div>

 <div class="form-row">
 <div class="field" style="flex:1">
 <label>استان <span style="color:red">*</span></label>
 <select name="locations[${idx}][province_id]" id="loc_prov_${idx}" required>
 ${provOptions}
 </select>
 </div>
 <div class="field fade-in" id="loc_city_wrap_${idx}" style="flex:1;display:none">
 <label>شهر / شهرستان <span style="color:red">*</span></label>
 <select name="locations[${idx}][city_id]" id="loc_city_${idx}" required>
 <option value="">-- انتخاب شهر --</option>
 </select>
 </div>
 </div>

 <div class="field fade-in" id="loc_addr_wrap_${idx}" style="display:none;margin-top:6px">
 <label>آدرس دقیق پستی <span style="color:red">*</span></label>
 <input name="locations[${idx}][address]" id="loc_addr_${idx}" placeholder="خیابان، کوچه، پلاک" required>
 </div>

 <div class="form-row" style="margin-bottom:0;margin-top:10px">
 <div class="field" style="flex:1;margin-bottom:0">
 <label>تلفن مستقیم ۱ این مکان (اختیاری)</label>
 <input name="locations[${idx}][phone]" placeholder="مثال: 02128421075">
 </div>
 <div class="field" style="flex:1;margin-bottom:0">
 <label>تلفن مستقیم ۲ این مکان (اختیاری)</label>
 <input name="locations[${idx}][phone2]" placeholder="مثال: 02128421076">
 </div>
 </div>
 </div>
 `;

 container.insertAdjacentHTML('beforeend', cardHtml);
 updateLocationNumbers();
 initLocationCascade(idx);

 const newCard = document.getElementById('loc_card_' + idx);
 if (newCard) {
 newCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
 }
}

function toggleServices() {
 let isCarton = false;
 let isPrint = false;
 let isCartonMachinery = false;
 let isSheetMachinery = false;
 let isService = false;

 if (Array.isArray(selectedCategories)) {
 selectedCategories.forEach(c => {
 const slug = c.slug || '';
 const pillarSlug = c.pillar_slug || '';
 const label = ((c.name || '') + ' ' + (c.full_path || '')).replace(/\u200c/g, ' ');

 if (pillarSlug === 'printing-services' || label.includes('چاپخانه')) {
 isPrint = true;
 }
 if (pillarSlug === 'service-providers') {
 isService = true;
 }
 if (
 ['carton-making-machinery-importers', 'carton-making-machinery-manufacturers', 'carton-making-machinery-sellers'].includes(slug) ||
 label.includes('ماشین آلات کارتن')
 ) {
 isCartonMachinery = true;
 }
 if (
 ['corrugated-board-machinery-importers', 'corrugated-board-machinery-manufacturers', 'corrugated-board-machinery-sellers'].includes(slug) ||
 label.includes('ماشین آلات ورق')
 ) {
 isSheetMachinery = true;
 }
 if (
 ['corrugated-board', 'corrugated-board-stores', 'carton-box', 'carton-box-stores', 'postal-box', 'postal-box-stores'].includes(slug) ||
 ((label.includes('ورق کارتن') || label.includes('جعبه کارتن')) && !label.includes('ماشین'))
 ) {
 isCarton = true;
 }
 });
 }

 const sec = document.getElementById('services_section');
 const boxCarton = document.getElementById('group_carton');
 const boxPrint = document.getElementById('group_printing');
 const boxPostPress = document.getElementById('group_post_press');
 const boxCartonMachine = document.getElementById('group_carton_machinery');
 const boxSheetMachine = document.getElementById('group_sheet_machinery');
 const boxMachineSrv = document.getElementById('group_machine_services');

 const hasAny = (isCarton || isPrint || isCartonMachinery || isSheetMachinery || isService);
 if (sec) sec.style.display = hasAny ? 'block' : 'none';
 if (boxCarton) boxCarton.style.display = isCarton ? 'block' : 'none';
 if (boxPrint) boxPrint.style.display = isPrint ? 'block' : 'none';
 if (boxPostPress) boxPostPress.style.display = isPrint ? 'block' : 'none';
 if (boxCartonMachine) boxCartonMachine.style.display = isCartonMachinery ? 'block' : 'none';
 if (boxSheetMachine) boxSheetMachine.style.display = isSheetMachinery ? 'block' : 'none';
 if (boxMachineSrv) boxMachineSrv.style.display = isService ? 'block' : 'none';

 toggleTechSpecs();
}

function toggleTechSpecs() {
 let hasFactory = false;
 let isSheetPaperFactory = false;
 let hasPrint = false;
 let hasTrade = false;
 let isSheetPaperTrade = false;
 let isCustomService = false;
 let isTechnicalService = false;

 const sheetPaperKeywords = ['کاغذ', 'مقوا', 'ورق کارتن', 'جعبه کارتن', 'تیشو', 'سینگل فیس', 'فویل و فیلم', 'بوبین مقوایی', 'نبشی مقوایی', 'قوطی مقوایی', 'پاکت بسته بندی', 'جعبه مقوایی'];
 const nonSheetKeywords = ['ماشین', 'مواد شیمیایی', 'شیرآلات', 'تجهیزات', 'قطعات', 'چسب', 'رنگ', 'مواد مصرفی', 'پوشک', 'نوار بهداشتی', 'ظروف یکبارمصرف', 'آخال', 'لیوان'];

 if (Array.isArray(selectedCategories)) {
 selectedCategories.forEach(c => {
 const slug = c.slug || '';
 const pillarSlug = c.pillar_slug || '';
 const label = ((c.name || '') + ' ' + (c.full_path || '')).replace(/\u200c/g, ' ');
 const isSheetPaper = sheetPaperKeywords.some(k => label.includes(k)) && !nonSheetKeywords.some(k => label.includes(k));

 if (pillarSlug === 'factories') {
 hasFactory = true;
 if (isSheetPaper) isSheetPaperFactory = true;
 }
 if (pillarSlug === 'printing-services' || label.includes('چاپخانه')) {
 hasPrint = true;
 }
 if (pillarSlug === 'stores' || pillarSlug === 'importers') {
 hasTrade = true;
 if (isSheetPaper) isSheetPaperTrade = true;
 }
 if (pillarSlug === 'service-providers') {
 if (slug === 'die-making' || slug === 'post-press-services' || label.includes('قالب سازی') || label.includes('خدمات پس از چاپ')) {
 isCustomService = true;
 }
 if (['installation-of-machines', 'water-treatment', 'grinding'].includes(slug) || label.includes('نصب') || label.includes('تصفیه') || label.includes('سنگ زنی')) {
 isTechnicalService = true;
 }
 }
 });
 }

 const showCapacity = hasFactory || hasPrint;
 const showWidth = isSheetPaperFactory;
 const showGsm = isSheetPaperFactory || isSheetPaperTrade;
 const showMoq = hasFactory || hasPrint || hasTrade;
 const showCustomOrder = hasFactory || hasPrint || isCustomService;
 const showCerts = hasFactory || hasPrint || hasTrade || isCustomService || isTechnicalService;
 const showAnyField = showCapacity || showWidth || showGsm || showMoq || showCustomOrder;
 const showSection = showAnyField || showCerts;

 const sec = document.getElementById('tech_specs_section');
 const grid = document.getElementById('tech_specs_fields_grid');
 const titleEl = document.getElementById('tech_specs_title');
 const badgeEl = document.getElementById('tech_specs_badge');
 const labelCapacity = document.getElementById('label_capacity');
 const labelGsm = document.getElementById('label_gsm_range');
 const labelCustom = document.getElementById('label_custom_order');

 if (sec) sec.style.display = showSection ? 'block' : 'none';
 if (grid) grid.style.display = showAnyField ? 'grid' : 'none';

 if (titleEl && badgeEl) {
 if (hasFactory || hasPrint) {
 titleEl.textContent = '۵. توانمندی‌های فنی و تولیدی';
 badgeEl.textContent = 'صنعتی';
 } else if (hasTrade) {
 titleEl.textContent = '۵. مشخصات بازرگانی و تأمین';
 badgeEl.textContent = 'بازرگانی';
 } else {
 titleEl.textContent = '۵. استانداردها و توانمندی‌های فنی';
 badgeEl.textContent = 'فنی';
 }
 }

 if (labelCapacity) {
 labelCapacity.textContent = (hasPrint && !hasFactory) ? 'ظرفیت چاپ / تولید ماهانه' : 'ظرفیت تولید ماهانه';
 }
 if (labelGsm) {
 labelGsm.textContent = (!isSheetPaperFactory && isSheetPaperTrade) ? 'محدوده گراماژ قابل تأمین' : 'محدوده گراماژ تولید';
 }
 if (labelCustom) {
 labelCustom.textContent = (isCustomService && !hasFactory && !hasPrint) ? 'امکان انجام سفارش اختصاصی' : 'امکان تولید اختصاصی / سفارشی';
 }

 const setFieldVisible = (id, visible) => {
 const el = document.getElementById(id);
 if (!el) return;
 el.style.display = visible ? '' : 'none';
 el.querySelectorAll('input, select').forEach(inp => { inp.disabled = !visible; });
 };

 setFieldVisible('field_moq', showMoq);
 setFieldVisible('field_capacity', showCapacity);
 setFieldVisible('field_production_width', showWidth);
 setFieldVisible('field_gsm_range', showGsm);
 setFieldVisible('field_custom_order', showCustomOrder);
 setFieldVisible('field_certificates', showCerts);
}

document.addEventListener('DOMContentLoaded', function() {
 renderCategoryTags();
 initCategorySearch();
 toggleServices();

 // Real-time email validation
 const emailInput = document.getElementById('email_input');
 const emailFeedback = document.getElementById('email_feedback');

 function validateEmailField(showSuccess = true) {
 if (!emailInput) return true;
 const val = emailInput.value.trim();
 if (!val) {
 emailInput.style.borderColor = '';
 emailInput.style.boxShadow = '';
 if (emailFeedback) {
 emailFeedback.style.display = 'none';
 emailFeedback.textContent = '';
 }
 return true;
 }

 const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
 if (emailRegex.test(val)) {
 emailInput.style.borderColor = '#16a34a';
 emailInput.style.boxShadow = '0 0 0 3px rgba(22, 163, 74, 0.12)';
 if (emailFeedback) {
 if (showSuccess) {
 emailFeedback.style.display = 'block';
 emailFeedback.style.color = '#16a34a';
 emailFeedback.textContent = ' فرمت ایمیل معتبر است';
 } else {
 emailFeedback.style.display = 'none';
 }
 }
 return true;
 } else {
 emailInput.style.borderColor = '#dc2626';
 emailInput.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.12)';
 if (emailFeedback) {
 emailFeedback.style.display = 'block';
 emailFeedback.style.color = '#dc2626';
 emailFeedback.textContent = ' فرمت ایمیل نامعتبر است (مثال: info@company.ir)';
 }
 return false;
 }
 }

 if (emailInput) {
 emailInput.addEventListener('input', () => validateEmailField(true));
 emailInput.addEventListener('blur', () => validateEmailField(true));
 if (emailInput.value.trim()) {
 validateEmailField(false);
 }
 }

 // Validate at least 1 category on form submission
 const companyForm = document.querySelector('form');
 if (companyForm) {
 companyForm.addEventListener('submit', function(e) {
 if (!selectedCategories || selectedCategories.length === 0) {
 e.preventDefault();
 alert('لطفاً حداقل یک دسته‌بندی یا زمینه فعالیت برای شرکت انتخاب نمایید.');
 const searchInput = document.getElementById('cat_search_input');
 if (searchInput) {
 searchInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
 searchInput.focus();
 }
 const noAlert = document.getElementById('no_categories_alert');
 if (noAlert) noAlert.style.display = 'block';
 return false;
 }

 if (emailInput && !validateEmailField(true)) {
 e.preventDefault();
 alert('لطفاً فرمت ایمیل را اصلاح فرمایید یا فیلد را خالی بگذارید.');
 emailInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
 emailInput.focus();
 return false;
 }
 });
 }

 // Initialize existing location cards
 @foreach($locList as $idx => $loc)
 initLocationCascade(
 '{{ $idx }}',
 '{{ $loc["province_id"] ?? $loc["province_name"] ?? "" }}',
 '{{ $loc["city_id"] ?? $loc["city_name"] ?? "" }}'
 );
 @endforeach

 updateLocationNumbers();

 // Limit gallery to max 5 images
 const galleryInput = document.getElementById('gallery_input');
 if (galleryInput) {
 galleryInput.addEventListener('change', function() {
 if (this.files && this.files.length > 5) {
 alert('حداکثر می‌توانید ۵ تصویر برای گالری انتخاب کنید.');
 this.value = '';
 }
 });
 }
});
</script>
@endsection
