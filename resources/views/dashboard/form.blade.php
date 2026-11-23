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
.cat-search-item:last-child {
  border-bottom: none;
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
.cat-badge-secondary {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: #f1f5f9;
  color: #334155;
  border: 1px solid #cbd5e1;
  padding: 5px 12px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 500;
}
.btn-cat-action {
  font-size: 11.5px;
  padding: 4px 10px;
  border-radius: 6px;
  border: 1px solid var(--border);
  background: #fff;
  cursor: pointer;
  white-space: nowrap;
  font-family: inherit;
  transition: all 0.15s;
}
.btn-cat-action:hover {
  background: var(--primary);
  color: #fff;
  border-color: var(--primary);
}
.btn-cat-action.primary-btn {
  background: #eff6ff;
  color: #1d4ed8;
  border-color: #bfdbfe;
  font-weight: 700;
}
.btn-cat-action.primary-btn:hover {
  background: #1d4ed8;
  color: #fff;
}
</style>

<div style="max-width:920px;margin:20px auto">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
    <h2 style="margin:0;font-size:22px;color:var(--primary)">{{ $company->exists ? 'ویرایش شرکت' : 'ثبت نام و اطلاعات شرکت' }}</h2>
    <a href="{{ route('dashboard') }}" class="btn ghost small">بازگشت به پنل من</a>
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

  <form method="post" action="{{ $company->exists ? route('companies.update', $company) : route('companies.store') }}" enctype="multipart/form-data">
    @csrf
    @if($company->exists) @method('PUT') @endif

    @php
      $f = fn($k) => old($k, $company->$k);
      $cVal = fn($sub, $k) => old("contacts.{$k}", data_get($company->contacts, $k));
      $tVal = fn($sub, $k) => old("tech_specs.{$k}", data_get($company->tech_specs, $k));
      $sVal = fn($sub, $k) => old("socials.{$k}", data_get($company->socials, $k));
      $hasAct = fn($type) => in_array($type, old('activity_types', (array)($company->activity_types ?? [])));
      $hasServ = fn($srv) => in_array($srv, old('services', (array)($company->services ?? [])));
      $hasCert = fn($cert) => in_array($cert, (array)old('tech_specs.certificates', (array)(data_get($company->tech_specs, 'certificates') ?? [])));

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

    <!-- بخش ۱: اطلاعات پایه -->
    <div class="form-card">
      <h3>
        <span>۱. اطلاعات پایه و شناسنامه شرکت</span>
        <span class="badge ok">اصلی</span>
      </h3>
      
      <div class="form-row">
        <div class="field">
          <label>نام شرکت یا کسب‌وکار <span style="color:red">*</span></label>
          <input name="name" value="{{ $f('name') }}" placeholder="مثال: شرکت صنایع کارتن‌سازی حریر پیران" required>
        </div>
        <div class="field">
          <label>شعار یا عنوان کوتاه تبلیغاتی</label>
          <input name="slogan" value="{{ $f('slogan') }}" placeholder="مثال: تولیدکننده تخصصی ورق و کارتن ۳ و ۵ لایه">
        </div>
      </div>

      <!-- کادر جستجوی سریع هوشمند دسته‌بندی -->
      <div class="cat-search-box">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
          <label for="cat_search_input" style="font-weight:700;color:var(--primary);margin:0;font-size:13.5px;cursor:pointer">
            🔍 جستجوی سریع زمینه فعالیت یا محصول (انتخاب مستقیم با تایپ):
          </label>
          <span style="font-size:12px;color:var(--muted)">نام کالا یا خدمت خود را بنویسید</span>
        </div>
        <div style="position:relative">
          <input type="text" id="cat_search_input" placeholder="مثال: کارتن، لاینر، گلاسه، سلفون، افست، طلاکوب، جعبه، چسب، ماشین‌آلات، هلیوگراور..." autocomplete="off" style="padding-right:36px;padding-left:70px;background:#fff">
          <span style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:15px;pointer-events:none">🔍</span>
          <button type="button" id="cat_search_clear" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);border:none;background:none;cursor:pointer;display:none;color:#ef4444;font-size:12px;font-weight:700;font-family:inherit">✕ پاک کردن</button>
        </div>
        <!-- نتایج شناور جستجو -->
        <div id="cat_search_results" class="cat-search-dropdown" style="display:none"></div>
      </div>

      <!-- بلوک دسته‌بندی اصلی -->
      <div style="background:#fafafa;border:1px solid var(--border);border-radius:10px;padding:14px;margin-bottom:14px">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px">
          <span class="badge" style="background:#dbeafe;color:#1e40af;font-weight:700;font-size:12.5px">🌟 دسته‌بندی و نقش اصلی شرکت</span>
          <span style="font-size:12px;color:var(--muted)">(هویت اصلی شما در دایرکتوری - می‌توانید از کادر جستجوی بالا یا منوهای زیر انتخاب کنید)</span>
        </div>
        <div class="form-row">
          <div class="field" style="flex:1">
            <label>شاخه اصلی فعالیت <span style="color:red">*</span></label>
            <select id="main_pillar_select" required>
              <option value="">-- انتخاب کنید: تولیدکننده، چاپخانه، فروشنده و... --</option>
              @foreach($categories as $pillar)
                <option value="{{ $pillar->id }}" data-slug="{{ $pillar->slug }}" @selected($initialPillarId == $pillar->id)>
                  {{ $pillar->icon }} {{ $pillar->name }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="field fade-in" id="sub_category_wrapper" style="flex:1;display:none">
            <label>دسته‌بندی تخصصی دایرکتوری <span style="color:red">*</span></label>
            <select name="category_id" id="sub_category_select" required>
              <option value="">-- انتخاب دسته‌بندی تخصصی --</option>
            </select>
          </div>
        </div>
      </div>

      <!-- بلوک نقش‌ها و دسته‌بندی‌های تکمیلی (چندمنظوره) -->
      <div style="border:1px dashed #cbd5e1;border-radius:10px;padding:14px;background:#fcfcfd;margin-bottom:14px">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;margin-bottom:10px">
          <div>
            <span style="font-weight:700;color:var(--text);font-size:13.5px">✨ زمینه‌ها و نقش‌های تکمیلی (ویژه شرکت‌های چندمنظوره):</span>
            <span style="font-size:12px;color:var(--muted);display:block;margin-top:2px">اگر علاوه بر فعالیت اصلی، در شاخه‌های دیگر هم فعالیت دارید (مثلاً هم تولیدکننده هستید و هم واردکننده یا فروشنده)، اینجا اضافه کنید.</span>
          </div>
          <button type="button" class="btn ghost btn-sm" id="btn_toggle_add_sec" style="font-size:12.5px;padding:5px 12px">
            ➕ افزودن شاخه تکمیلی
          </button>
        </div>

        <!-- فرم بازشونده انتخاب دستی دسته ثانویه -->
        <div id="sec_cat_picker_box" class="fade-in" style="display:none;background:#fff;border:1px solid #bfdbfe;border-radius:8px;padding:12px;margin-bottom:12px">
          <div class="form-row">
            <div class="field" style="flex:1">
              <label style="font-size:12.5px">نقش تکمیلی (مثلاً واردکننده، فروشنده، خدمات):</label>
              <select id="sec_pillar_select">
                <option value="">-- انتخاب نقش تکمیلی --</option>
                @foreach($categories as $pillar)
                  <option value="{{ $pillar->id }}" data-slug="{{ $pillar->slug }}">
                    {{ $pillar->icon }} {{ $pillar->name }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="field" id="sec_sub_wrapper" style="flex:1;display:none">
              <label style="font-size:12.5px">دسته‌بندی تخصصی تکمیلی:</label>
              <select id="sec_sub_select">
                <option value="">-- انتخاب دسته تخصصی --</option>
              </select>
            </div>
          </div>
          <div style="display:flex;gap:8px;margin-top:8px">
            <button type="button" class="btn btn-sm" id="btn_confirm_add_sec" style="font-size:12.5px">✓ افزودن به لیست تکمیلی</button>
            <button type="button" class="btn ghost btn-sm" id="btn_cancel_add_sec" style="font-size:12.5px">انصراف</button>
          </div>
        </div>

        <!-- لیست تگ‌های انتخاب‌شده ثانویه -->
        <div id="secondary_badges_list" style="display:flex;gap:8px;flex-wrap:wrap">
          <!-- توسط جاوااسکریپت پر می‌شود -->
        </div>
        <div id="no_secondary_msg" style="font-size:12.5px;color:#94a3b8;font-style:italic;margin-top:4px">
          هیچ دسته ثانویه‌ای اضافه نشده است (برای شرکت‌های تک‌منظوره نیازی به ثبت این بخش نیست).
        </div>
      </div>
    </div>

    <!-- بخش ۲: موقعیت‌های مکانی و آدرس‌ها (سیستم چندگانه پویا: کارخانه، دفتر، فروشگاه، انبار) -->
    <div class="form-card">
      <h3>
        <span>۲. موقعیت‌های مکانی و آدرس‌ها</span>
        <span class="badge ok">امکان ثبت چندگانه</span>
      </h3>
      <p style="margin:0 0 14px;color:var(--muted);font-size:13.5px">
        شما می‌توانید هر تعداد آدرس یا شعبه (کارخانه، دفتر مرکزی، فروشگاه/شوروم، انبار و...) را با مشخصات مجزا ثبت کنید.
      </p>

      <div id="locations_container">
        @foreach($locList as $idx => $loc)
          <div class="location-card" id="loc_card_{{ $idx }}" data-index="{{ $idx }}">
            <div class="location-header">
              <label style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;font-size:13px;color:var(--text);user-select:none">
                <input type="radio" name="primary_location_index" value="{{ $idx }}" @checked(!empty($loc['is_primary']) || $loop->first)>
                <span><b>آدرس اصلی</b></span>
              </label>
              <button type="button" class="btn-remove-loc" onclick="removeLocationBlock('{{ $idx }}')" style="{{ count($locList) > 1 ? '' : 'display:none;' }}">
                🗑️ حذف این آدرس
              </button>
            </div>

            <!-- نوع مکان -->
            <div class="field" style="margin-bottom:12px">
              <label>نوع مکان / ماهیت آدرس <span style="color:red">*</span></label>
              <select name="locations[{{ $idx }}][type]" required>
                @foreach($locationTypes as $typeKey => $typeInfo)
                  <option value="{{ $typeKey }}" @selected(($loc['type'] ?? '') == $typeKey)>{{ $typeInfo['icon'] }} {{ $typeInfo['title'] }}</option>
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
              <input name="locations[{{ $idx }}][address]" id="loc_addr_{{ $idx }}" value="{{ $loc['address'] ?? '' }}" placeholder="مثال: شهرک صنعتی، فاز ۲، خیابان تلاش، پلاک ۱۲" required>
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
        <span>➕ افزودن آدرس و شعبه دیگر (کارخانه دیگر، دفتر، فروشگاه، انبار...)</span>
      </button>
    </div>

    <!-- بخش ۳: محصولات و خدمات تخصصی شرطی -->
    <div class="form-card" id="services_section" style="display:none">
      <h3>
        <span>۳. محصولات و خدمات تخصصی</span>
        <span class="badge" style="background:#eef3f9;color:var(--primary)">هوشمند و تفکیک‌شده</span>
      </h3>

      <!-- ۱. گروه کارتن‌سازی -->
      <div id="group_carton" class="dynamic-box">
        <label style="font-weight:700;display:block;margin-bottom:8px;color:#1e3a8a">📦 خدمات و محصولات جعبه و کارتن‌سازی:</label>
        <div class="check-grid">
          @php
            $cartonItems = [
              'سینگل فیس', 'ورق ۳ لا', 'ورق ۵ لا', 'ورق ۷ لا',
              'فلوت A', 'فلوت B', 'فلوت C', 'فلوت E', 'فلوت F', 'فلوت T',
              'چاپ ۱ رنگ', 'چاپ ۲ رنگ', 'چاپ ۳ رنگ', 'چاپ ۴ رنگ', 'چاپ ۵ رنگ', 'چاپ ۶ رنگ', 'چاپ ۷ رنگ', 'چاپ ۸ رنگ',
              'کارتن معمولی', 'کارتن درب دوبل', 'سینی', 'کارتن دایکاتی', 'کارتن تلسکوپی', 'کارتن لمینتی', 'منقسم', 'کارتن پستی'
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

      <!-- ۲. گروه تکنولوژی‌های چاپ -->
      <div id="group_printing" class="dynamic-box" style="margin-top:16px">
        <label style="font-weight:700;display:block;margin-bottom:8px;color:#7c2d12">🖨️ سیستم‌ها و تکنولوژی‌های چاپ موجود در مجموعه:</label>
        <div class="check-grid">
          @php
            $printItems = [
              'آتلیه طراحی و لیتوگرافی', 'فاقد چاپخانه (واسطه)',
              'چاپ افست ۱ ورقی', 'چاپ افست ۲ ورقی', 'چاپ افست ۲/۵ ورقی', 'چاپ افست ۴/۵ ورقی', 'چاپ افست ۶ ورقی', 'چاپ افست رول (وب)',
              'چاپ فلکسو', 'چاپ هلیوگراور', 'چاپ دیجیتال ورقی', 'چاپ دیجیتال رول', 'چاپ سیلک اسکرین', 'چاپ رول و لیبل'
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

      <!-- ۳. گروه خدمات پس از چاپ و تکمیلی -->
      <div id="group_post_press" class="dynamic-box" style="margin-top:16px">
        <label style="font-weight:700;display:block;margin-bottom:8px;color:#0e7490">✂️ خدمات پس از چاپ، پوشش و مونتاژ (Post-Press):</label>
        <div class="check-grid">
          @php
            $postPressItems = [
              'سلفون‌کشی حرارتی', 'سلفون‌کشی واتربیس', 'یووی موضعی', 'یووی سیلندری', 'ورنی افست',
              'طلاکوب و نقره‌کوب', 'برجسته‌سازی (امباس)', 'دایکات فکی', 'دایکات اتوماتیک بوبست', 'لترپرس و خط تا',
              'جعبه‌چسبانی ساده', 'جعبه‌چسبانی لاک‌باتم', 'پنجره‌چسبانی (طلق)', 'لمینت کارتن و مقوا',
              'صحافی چسب گرم', 'صحافی منگنه لوپ و تخت', 'صحافی فنر دوبل'
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

      <!-- ۴. گروه محصولات جعبه و بسته‌بندی نهایی -->
      <div id="group_print_products" class="dynamic-box" style="margin-top:16px">
        <label style="font-weight:700;display:block;margin-bottom:8px;color:#4338ca">📦 محصولات نهایی چاپی و بسته‌بندی مقوایی:</label>
        <div class="check-grid">
          @php
            $productItems = [
              'جعبه مقوایی دارویی و بهداشتی', 'جعبه شیرینی و فست‌فود', 'هاردباکس (جعبه سخت لوکس)',
              'لیبل و برچسب پشت‌چسب‌دار', 'ساک دستی کاغذی و مقوایی', 'کاتالوگ، بروشور و فولدر',
              'جعبه و کارتن لمینتی', 'پوستر و بسته‌بندی صادراتی'
            ];
          @endphp
          @foreach($productItems as $item)
            <label class="check-item">
              <input type="checkbox" name="services[]" value="{{ $item }}" @checked($hasServ($item))>
              <span>{{ $item }}</span>
            </label>
          @endforeach
        </div>
      </div>

      <!-- ۵. گروه نصب و خدمات فنی ماشین‌آلات -->
      <div id="group_machine_services" class="dynamic-box" style="margin-top:16px">
        <label style="font-weight:700;display:block;margin-bottom:8px;color:#b45309">🛠️ نصب، تعمیرات و خدمات فنی ماشین‌آلات:</label>
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

      <!-- ۶. گروه ماشین‌آلات و خطوط تولید -->
      <div id="group_machinery" class="dynamic-box" style="margin-top:16px">
        <label style="font-weight:700;display:block;margin-bottom:8px;color:#065f46">⚙️ تجهیزات و ماشین‌آلات ورق‌سازی، کارتن و چاپ:</label>
        <div class="check-grid">
          @php
            $machineItems = [
              'پری هیتر', 'تری پری هیتر', 'پری کاندیشنر', 'رول گیر کاغذ', 'ترمز سینگل',
              'سینگل فیسر', 'دستگاه چسب زن', 'دابل فیسر', 'اسلیتر اسکورر', 'کاتاف الکترونیکی',
              'دستگاه کاتاف دوبل', 'دستگاه کاتاف هلیکال', 'دستگاه استاکر', 'استاکر دوبل', 'سیلندر کروگیت',
              'ماشین چاپ افست', 'ماشین دایکات بوبست', 'دستگاه جعبه چسبان'
            ];
          @endphp
          @foreach($machineItems as $item)
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
        <span>۴. شماره‌های تماس و افراد کلیدی (B2B)</span>
        <span class="badge" style="background:#f4f6f9">دسترسی خریداران</span>
      </h3>

      <div class="form-row-3">
        <div class="field">
          <label>شماره تلفن ثابت اصلی دفتر مرکزی <span style="color:red">*</span></label>
          <input name="phone" value="{{ $f('phone') }}" placeholder="مثال: 02128421075" required>
        </div>
        <div class="field">
          <label>شماره موبایل پشتیبانی / پیام‌رسان‌ها</label>
          <input name="contacts[support_mobile]" value="{{ $cVal('contacts', 'support_mobile') }}" placeholder="مثال: 09121234567">
        </div>
        <div class="field">
          <label>آدرس ایمیل رسمی شرکت (اختیاری)</label>
          <input type="email" name="email" value="{{ $f('email') }}" placeholder="مثال: info@company.ir">
        </div>
      </div>

      <div style="margin-top:14px;background:#fbfcfe;border:1px solid var(--border);border-radius:10px;padding:16px">
        <h4 style="margin:0 0 12px;font-size:14.5px;color:var(--text)">👥 مشخصات مدیران و مسئولین شرکت:</h4>
        
        <div class="form-row" style="margin-bottom:12px">
          <div class="field" style="margin-bottom:0">
            <label>نام و نام خانوادگی مدیرعامل</label>
            <input name="contacts[ceo_name]" value="{{ $cVal('contacts', 'ceo_name') }}">
          </div>
          <div class="field" style="margin-bottom:0">
            <label>شماره تماس مستقیم مدیرعامل</label>
            <input name="contacts[ceo_phone]" value="{{ $cVal('contacts', 'ceo_phone') }}" placeholder="09...">
          </div>
        </div>

        <div class="form-row" style="margin-bottom:12px">
          <div class="field" style="margin-bottom:0">
            <label>نام و نام خانوادگی مدیر فروش</label>
            <input name="contacts[sales_name]" value="{{ $cVal('contacts', 'sales_name') }}">
          </div>
          <div class="field" style="margin-bottom:0">
            <label>شماره تماس مستقیم مدیر فروش</label>
            <input name="contacts[sales_phone]" value="{{ $cVal('contacts', 'sales_phone') }}" placeholder="09...">
          </div>
        </div>

        <div class="form-row">
          <div class="field" style="margin-bottom:0">
            <label>نام مسئول خرید / تدارکات</label>
            <input name="contacts[purchase_name]" value="{{ $cVal('contacts', 'purchase_name') }}">
          </div>
          <div class="field" style="margin-bottom:0">
            <label>شماره تماس مسئول خرید</label>
            <input name="contacts[purchase_phone]" value="{{ $cVal('contacts', 'purchase_phone') }}" placeholder="09...">
          </div>
        </div>
      </div>
    </div>

    <!-- بخش ۵: مشخصات فنی و توان تولید -->
    <div class="form-card">
      <h3>
        <span>۵. مشخصات فنی و توانمندی‌های کارخانه</span>
        <span class="badge" style="background:#f4f6f9">صنعتی</span>
      </h3>

      <div class="form-row-3">
        <div class="field">
          <label>سال تأسیس</label>
          <input name="tech_specs[established_year]" value="{{ $tVal('tech_specs', 'established_year') }}" placeholder="مثال: 1392">
        </div>
        <div class="field">
          <label>حداقل تیراژ سفارش (MOQ)</label>
          <input name="tech_specs[moq]" value="{{ $tVal('tech_specs', 'moq') }}" placeholder="مثال: ۵۰۰۰ عدد یا ۱۰ تن">
        </div>
        <div class="field">
          <label>ظرفیت تولید ماهانه</label>
          <input name="tech_specs[capacity]" value="{{ $tVal('tech_specs', 'capacity') }}" placeholder="مثال: ۵۰۰ تن در ماه">
        </div>
      </div>

      <div class="form-row-3">
        <div class="field">
          <label>عرض تولید دستگاه‌ها</label>
          <input name="tech_specs[production_width]" value="{{ $tVal('tech_specs', 'production_width') }}" placeholder="مثال: ۲۲۰ سانتی‌متر">
        </div>
        <div class="field">
          <label>محدوده گراماژ تولید</label>
          <input name="tech_specs[gsm_range]" value="{{ $tVal('tech_specs', 'gsm_range') }}" placeholder="مثال: ۱۱۰ تا ۴۰۰ گرم">
        </div>
        <div class="field">
          <label>امکان تولید اختصاصی / سفارشی</label>
          <select name="tech_specs[custom_order]">
            <option value="بله" @selected($tVal('tech_specs', 'custom_order') == 'بله')>بله، پذیرفته می‌شود</option>
            <option value="خیر" @selected($tVal('tech_specs', 'custom_order') == 'خیر')>خیر</option>
          </select>
        </div>
      </div>

      <div class="field" style="margin-top:10px;margin-bottom:0">
        <label>گواهینامه‌های کیفی و استانداردها:</label>
        <div class="check-grid">
          @php
            $certs = ['ISO 9001', 'ISO 14001', 'ISO 45001', 'ISO 22000', 'نشان استاندارد ملی', 'سیب سلامت / بهداشت', 'پروانه بهره‌برداری'];
          @endphp
          @foreach($certs as $cert)
            <label class="check-item">
              <input type="checkbox" name="tech_specs[certificates][]" value="{{ $cert }}" @checked($hasCert($cert))>
              <span>{{ $cert }}</span>
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

    <!-- بخش ۷: رسانه‌ها، فایل‌ها و کاتالوگ -->
    <div class="form-card">
      <h3>
        <span>۷. رسانه‌ها، تصاویر و کاتالوگ</span>
        <span class="premium-tag">امکانات ویژه</span>
      </h3>

      <div class="form-row">
        <div class="field">
          <label>لوگوی شرکت (تصویر باکیفیت)</label>
          @if($company->logo)
            <div style="margin-bottom:6px"><img src="{{ asset($company->logo) }}" height="50" style="border-radius:6px;border:1px solid var(--border)"></div>
          @endif
          <input type="file" name="logo" accept="image/png,image/jpeg,image/webp">
          <div class="note">فرمت‌های مجاز: PNG, JPG, WEBP (حداکثر ۴ مگابایت)</div>
        </div>

        <div class="field">
          <label>📄 کاتالوگ محصولات و خدمات (فایل PDF)</label>
          @if($company->catalog)
            <div style="margin-bottom:6px"><a href="{{ asset($company->catalog) }}" target="_blank" style="font-weight:700">مشاهده کاتالوگ فعلی</a></div>
          @endif
          <input type="file" name="catalog" accept="application/pdf">
          <div class="note">فرمت مجاز: PDF (حداکثر ۲۵ مگابایت)</div>
        </div>
      </div>

      <div class="field" style="margin-top:10px">
        <label>📸 گالری تصاویر محصولات یا کارخانه (حداکثر ۵ تصویر)</label>
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

      <div class="field" style="margin-top:10px;margin-bottom:0">
        <label>🎥 کد ویدیوی آپارات یا لینک ویدیو</label>
        <input name="video_code" value="{{ $f('video_code') }}" placeholder="مثال: کد یا لینک اشتراک‌گذاری آپارات">
        <div class="note">در صورت وارد کردن، پلیر ویدیوی معرفی کارخانه در صفحه شرکت نمایش داده می‌شود.</div>
      </div>
    </div>

    <!-- بخش ۸: وب‌سایت و شبکه‌های اجتماعی -->
    <div class="form-card">
      <h3>
        <span>۸. وب‌سایت و شبکه‌های اجتماعی</span>
      </h3>

      <div class="form-row">
        <div class="field">
          <label>آدرس وب‌سایت رسمی</label>
          <input name="website" value="{{ $f('website') }}" placeholder="مثال: 2kilopaper.ir">
        </div>
        <div class="field">
          <label>آیدی اینستاگرام</label>
          <input name="socials[instagram]" value="{{ $sVal('socials', 'instagram') }}" placeholder="مثال: 2kilopaper">
        </div>
      </div>

      <div class="form-row" style="margin-top:10px">
        <div class="field">
          <label>کانال یا آیدی تلگرام</label>
          <input name="socials[telegram]" value="{{ $sVal('socials', 'telegram') }}" placeholder="مثال: kilopaper2">
        </div>
        <div class="field">
          <label>شناسه پیام‌رسان بله یا ایتا</label>
          <input name="socials[bale]" value="{{ $sVal('socials', 'bale') }}" placeholder="مثال: ble.ir/2kilopaper">
        </div>
      </div>
    </div>

    <div style="display:flex;gap:12px;align-items:center;margin-top:20px">
      <button type="submit" class="btn" style="padding:12px 32px;font-size:16px;font-weight:700">
        {{ $company->exists ? 'ذخیره تغییرات شرکت' : 'ثبت نهایی اطلاعات شرکت' }}
      </button>
      <a href="{{ route('dashboard') }}" class="btn ghost">انصراف</a>
    </div>
  </form>
</div>

<script>
const locationsTree = @json($locationsTree);
const locationTypesData = @json($locationTypes);
const categoriesTree = @json($categoriesTree);
const categoriesFlat = @json($categoriesFlat);
let secondaryCategories = @json($existingSecondaryCategories);
const initialPillarId = '{{ $initialPillarId ?? "" }}';
const initialCategoryId = '{{ old("category_id", $company->category_id) }}';
let nextLocIndex = {{ count($locList) }};

function initCategoryCascade() {
  const pillarSelect = document.getElementById('main_pillar_select');
  const subCatWrapper = document.getElementById('sub_category_wrapper');
  const subCatSelect = document.getElementById('sub_category_select');

  if (!pillarSelect || !subCatWrapper || !subCatSelect) return;

  window.renderMainSubCategories = function(pillarId, selectedVal = '') {
    subCatSelect.innerHTML = '<option value="">-- انتخاب دسته‌بندی تخصصی --</option>';
    if (!pillarId) {
      subCatWrapper.style.display = 'none';
      return;
    }

    const pillar = categoriesTree.find(p => p.id == pillarId);
    if (!pillar || !pillar.children || pillar.children.length === 0) {
      subCatWrapper.style.display = 'none';
      return;
    }

    pillar.children.forEach(ch => {
      if (ch.sub_children && ch.sub_children.length > 0) {
        const optgroup = document.createElement('optgroup');
        optgroup.label = ch.name;

        const parentOpt = document.createElement('option');
        parentOpt.value = ch.id;
        parentOpt.textContent = ch.name + ' (همه موارد / شاخه کلی)';
        if (selectedVal && selectedVal == ch.id) {
          parentOpt.selected = true;
        }
        optgroup.appendChild(parentOpt);

        ch.sub_children.forEach(sub => {
          const opt = document.createElement('option');
          opt.value = sub.id;
          opt.textContent = '— ' + sub.name;
          if (selectedVal && selectedVal == sub.id) {
            opt.selected = true;
          }
          optgroup.appendChild(opt);
        });

        subCatSelect.appendChild(optgroup);
      } else {
        const opt = document.createElement('option');
        opt.value = ch.id;
        opt.textContent = ch.name;
        if (selectedVal && selectedVal == ch.id) {
          opt.selected = true;
        }
        subCatSelect.appendChild(opt);
      }
    });

    subCatWrapper.style.display = 'block';
  };

  pillarSelect.addEventListener('change', function() {
    window.renderMainSubCategories(this.value);
    toggleServices();
  });

  // Initial load
  if (initialPillarId) {
    pillarSelect.value = initialPillarId;
    window.renderMainSubCategories(initialPillarId, initialCategoryId);
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
    }).slice(0, 15);

    if (matches.length === 0) {
      resultsBox.innerHTML = `
        <div style="padding:14px;text-align:center;color:var(--muted);font-size:13px">
          موردی با عنوان «<b>${term}</b>» یافت نشد. می‌توانید از منوهای زیر شاخه مورد نظر را انتخاب نمایید.
        </div>
      `;
      resultsBox.style.display = 'block';
      return;
    }

    let html = '';
    matches.forEach(item => {
      html += `
        <div class="cat-search-item" data-id="${item.id}">
          <div style="flex:1">
            <div style="font-weight:700;font-size:13.5px;color:var(--text);display:flex;align-items:center;gap:6px">
              <span class="cat-badge-primary">${item.pillar_icon} ${item.pillar_name}</span>
              <span>${highlight(item.name, q)}</span>
            </div>
            <div style="font-size:11.5px;color:var(--muted);margin-top:3px">${item.full_path}</div>
          </div>
          <div style="display:flex;gap:6px;align-items:center">
            <button type="button" class="btn-cat-action primary-btn" onclick="applySearchSelection(${item.id}, true)">
              🌟 اصلی
            </button>
            <button type="button" class="btn-cat-action" onclick="applySearchSelection(${item.id}, false)">
              ➕ تکمیلی
            </button>
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
      input.focus();
    });
  }

  document.addEventListener('click', function(e) {
    if (!input.contains(e.target) && !resultsBox.contains(e.target)) {
      resultsBox.style.display = 'none';
    }
  });
}

function applySearchSelection(catId, isPrimary) {
  const cat = categoriesFlat.find(c => c.id == catId);
  if (!cat) return;

  if (isPrimary) {
    const pillarSelect = document.getElementById('main_pillar_select');
    if (pillarSelect) {
      pillarSelect.value = cat.pillar_id;
      if (window.renderMainSubCategories) {
        window.renderMainSubCategories(cat.pillar_id, cat.id);
      }
      toggleServices();
    }
  } else {
    if (window.addSecondaryCategoryById) {
      window.addSecondaryCategoryById(catId);
    }
  }

  const resultsBox = document.getElementById('cat_search_results');
  if (resultsBox) resultsBox.style.display = 'none';
}

function initSecondaryCategories() {
  const container = document.getElementById('secondary_badges_list');
  const noMsg = document.getElementById('no_secondary_msg');
  const toggleBtn = document.getElementById('btn_toggle_add_sec');
  const pickerBox = document.getElementById('sec_cat_picker_box');
  const secPillar = document.getElementById('sec_pillar_select');
  const secSubWrap = document.getElementById('sec_sub_wrapper');
  const secSub = document.getElementById('sec_sub_select');
  const confirmBtn = document.getElementById('btn_confirm_add_sec');
  const cancelBtn = document.getElementById('btn_cancel_add_sec');

  function renderBadges() {
    if (!container) return;
    container.innerHTML = '';
    if (!secondaryCategories || secondaryCategories.length === 0) {
      if (noMsg) noMsg.style.display = 'block';
      return;
    }
    if (noMsg) noMsg.style.display = 'none';

    secondaryCategories.forEach(cat => {
      const div = document.createElement('div');
      div.className = 'cat-badge-secondary fade-in';
      div.id = 'sec_cat_tag_' + cat.id;
      div.innerHTML = `
        <span>${cat.pillar_icon || '📁'} <b>${cat.pillar_name || 'تکمیلی'}:</b> ${cat.name}</span>
        <input type="hidden" name="secondary_category_ids[]" value="${cat.id}">
        <button type="button" onclick="removeSecondaryCategory(${cat.id})" style="border:none;background:none;color:#ef4444;cursor:pointer;font-weight:700;padding:0 3px;font-size:14px;line-height:1" title="حذف این دسته">✕</button>
      `;
      container.appendChild(div);
    });
  }

  window.renderSecondaryBadges = renderBadges;

  window.addSecondaryCategoryById = function(catId) {
    const primaryCatId = document.getElementById('sub_category_select')?.value;
    if (primaryCatId && primaryCatId == catId) {
      alert('این دسته هم‌اکنون به عنوان «دسته‌بندی اصلی» شرکت انتخاب شده است.');
      return;
    }
    if (secondaryCategories.some(c => c.id == catId)) {
      alert('این دسته قبلاً به لیست زمینه‌های تکمیلی اضافه شده است.');
      return;
    }
    const cat = categoriesFlat.find(c => c.id == catId);
    if (cat) {
      secondaryCategories.push(cat);
      renderBadges();
      toggleServices();
    }
  };

  window.removeSecondaryCategory = function(catId) {
    secondaryCategories = secondaryCategories.filter(c => c.id != catId);
    renderBadges();
    toggleServices();
  };

  if (toggleBtn && pickerBox) {
    toggleBtn.addEventListener('click', function() {
      pickerBox.style.display = pickerBox.style.display === 'none' ? 'block' : 'none';
    });
  }

  if (cancelBtn && pickerBox) {
    cancelBtn.addEventListener('click', function() {
      pickerBox.style.display = 'none';
    });
  }

  if (secPillar && secSub && secSubWrap) {
    secPillar.addEventListener('change', function() {
      const pId = this.value;
      secSub.innerHTML = '<option value="">-- انتخاب دسته تخصصی --</option>';
      if (!pId) {
        secSubWrap.style.display = 'none';
        return;
      }
      const pillar = categoriesTree.find(p => p.id == pId);
      if (!pillar || !pillar.children) {
        secSubWrap.style.display = 'none';
        return;
      }
      pillar.children.forEach(ch => {
        if (ch.sub_children && ch.sub_children.length > 0) {
          const grp = document.createElement('optgroup');
          grp.label = ch.name;
          const pOpt = document.createElement('option');
          pOpt.value = ch.id;
          pOpt.textContent = ch.name + ' (همه موارد / کلی)';
          grp.appendChild(pOpt);
          ch.sub_children.forEach(sub => {
            const opt = document.createElement('option');
            opt.value = sub.id;
            opt.textContent = '— ' + sub.name;
            grp.appendChild(opt);
          });
          secSub.appendChild(grp);
        } else {
          const opt = document.createElement('option');
          opt.value = ch.id;
          opt.textContent = ch.name;
          secSub.appendChild(opt);
        }
      });
      secSubWrap.style.display = 'block';
    });
  }

  if (confirmBtn && secSub) {
    confirmBtn.addEventListener('click', function() {
      const catId = secSub.value;
      if (!catId) {
        alert('لطفاً دسته‌بندی تخصصی تکمیلی را انتخاب نمایید.');
        return;
      }
      addSecondaryCategoryById(catId);
      pickerBox.style.display = 'none';
      secPillar.value = '';
      secSub.innerHTML = '<option value="">-- انتخاب دسته تخصصی --</option>';
      secSubWrap.style.display = 'none';
    });
  }

  renderBadges();
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
        <label style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;font-size:13px;color:var(--text);user-select:none">
          <input type="radio" name="primary_location_index" value="${idx}">
          <span><b>آدرس اصلی</b></span>
        </label>
        <button type="button" class="btn-remove-loc" onclick="removeLocationBlock('${idx}')">
          🗑️ حذف این آدرس
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
        <input name="locations[${idx}][address]" id="loc_addr_${idx}" placeholder="مثال: شهرک صنعتی، فاز ۲، خیابان تلاش، پلاک ۱۲" required>
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
  const pillarSelect = document.getElementById('main_pillar_select');
  const selectedSlug = pillarSelect?.options[pillarSelect.selectedIndex]?.dataset?.slug || '';

  const activeSlugs = new Set();
  if (selectedSlug) activeSlugs.add(selectedSlug);
  if (Array.isArray(secondaryCategories)) {
    secondaryCategories.forEach(c => {
      if (c.pillar_slug) activeSlugs.add(c.pillar_slug);
    });
  }

  const isCarton = activeSlugs.has('factories');
  const isPrint = activeSlugs.has('printing-services');
  const isService = activeSlugs.has('service-providers');
  const isMachinery = activeSlugs.has('stores') || activeSlugs.has('importers') || activeSlugs.has('service-providers');

  const sec = document.getElementById('services_section');
  const boxCarton = document.getElementById('group_carton');
  const boxPrint = document.getElementById('group_printing');
  const boxPostPress = document.getElementById('group_post_press');
  const boxPrintProducts = document.getElementById('group_print_products');
  const boxMachine = document.getElementById('group_machinery');
  const boxMachineSrv = document.getElementById('group_machine_services');

  const hasAny = (isCarton || isPrint || isService || isMachinery);
  if (sec) sec.style.display = hasAny ? 'block' : 'none';
  if (boxCarton) boxCarton.style.display = isCarton ? 'block' : 'none';
  if (boxPrint) boxPrint.style.display = isPrint ? 'block' : 'none';
  if (boxPostPress) boxPostPress.style.display = (isPrint || isService) ? 'block' : 'none';
  if (boxPrintProducts) boxPrintProducts.style.display = (isPrint || isCarton) ? 'block' : 'none';
  if (boxMachine) boxMachine.style.display = isMachinery ? 'block' : 'none';
  if (boxMachineSrv) boxMachineSrv.style.display = (isService || isMachinery) ? 'block' : 'none';
}

document.addEventListener('DOMContentLoaded', function() {
  initCategoryCascade();
  initCategorySearch();
  initSecondaryCategories();
  toggleServices();

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
