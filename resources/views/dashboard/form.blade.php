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

      <div class="field">
        <label>دسته‌بندی اصلی در دایرکتوری <span style="color:red">*</span></label>
        <select name="category_id" required>
          <option value="">-- انتخاب دسته‌بندی اصلی --</option>
          @foreach($categories as $pillar)
            <optgroup label="{{ $pillar->icon }} {{ $pillar->name }}">
              @foreach($pillar->children as $ch)
                <option value="{{ $ch->id }}" @selected($f('category_id') == $ch->id)>{{ $ch->name }}</option>
              @endforeach
            </optgroup>
          @endforeach
        </select>
      </div>

      <div class="field" style="margin-top:12px;margin-bottom:0">
        <label><b>حوزه‌های کلیدی فعالیت شرکت:</b> (می‌توانید چند مورد را انتخاب کنید تا پنل‌های تخصصی خدمات باز شوند)</label>
        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:8px">
          <label class="activity-pill">
            <input type="checkbox" name="activity_types[]" value="carton_maker" id="act_carton" @checked($hasAct('carton_maker')) onchange="toggleServices()">
            <span>📦 تولیدکننده جعبه و کارتن</span>
          </label>
          <label class="activity-pill">
            <input type="checkbox" name="activity_types[]" value="printing" id="act_print" @checked($hasAct('printing')) onchange="toggleServices()">
            <span>🖨️ چاپخانه و مجتمع چاپ</span>
          </label>
          <label class="activity-pill">
            <input type="checkbox" name="activity_types[]" value="post_press" id="act_post_press" @checked($hasAct('post_press')) onchange="toggleServices()">
            <span>✂️ خدمات پس از چاپ و تکمیلی</span>
          </label>
          <label class="activity-pill">
            <input type="checkbox" name="activity_types[]" value="machinery" id="act_machine" @checked($hasAct('machinery')) onchange="toggleServices()">
            <span>⚙️ فروش یا واردات ماشین‌آلات</span>
          </label>
          <label class="activity-pill">
            <input type="checkbox" name="activity_types[]" value="machine_services" id="act_machine_srv" @checked($hasAct('machine_services')) onchange="toggleServices()">
            <span>🛠️ نصب و خدمات فنی ماشین‌آلات</span>
          </label>
          <label class="activity-pill">
            <input type="checkbox" name="activity_types[]" value="raw_material" id="act_raw" @checked($hasAct('raw_material')) onchange="toggleServices()">
            <span>📜 تأمین کاغذ، مقوا و مواد اولیه</span>
          </label>
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
              <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
                <span class="badge" style="background:#e0e7ff;color:#3730a3;font-weight:700">
                  📍 موقعیت شماره <span class="loc-number">{{ $loop->iteration }}</span>
                </span>
                <label style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;font-size:13px;color:var(--text);user-select:none">
                  <input type="radio" name="primary_location_index" value="{{ $idx }}" @checked(!empty($loc['is_primary']) || $loop->first)>
                  <span><b>آدرس و شهر اصلی دایرکتوری</b></span>
                </label>
              </div>
              <button type="button" class="btn-remove-loc" onclick="removeLocationBlock('{{ $idx }}')" style="{{ count($locList) > 1 ? '' : 'display:none;' }}">
                🗑️ حذف این آدرس
              </button>
            </div>

            <div class="form-row">
              <!-- نوع مکان -->
              <div class="field" style="flex:1">
                <label>نوع مکان / ماهیت آدرس <span style="color:red">*</span></label>
                <select name="locations[{{ $idx }}][type]" required>
                  @foreach($locationTypes as $typeKey => $typeInfo)
                    <option value="{{ $typeKey }}" @selected(($loc['type'] ?? '') == $typeKey)>{{ $typeInfo['icon'] }} {{ $typeInfo['title'] }}</option>
                  @endforeach
                </select>
              </div>

              <!-- عنوان شعبه -->
              <div class="field" style="flex:1">
                <label>عنوان یا نام اختصاصی این مکان (اختیاری)</label>
                <input name="locations[{{ $idx }}][title]" value="{{ $loc['title'] ?? '' }}" placeholder="مثال: شعبه ظهیرالاسلام، کارخانه شماره ۱، انبار کهریزک">
              </div>
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

            <!-- تلفن اختصاصی این مکان -->
            <div class="field" style="margin-bottom:0;margin-top:10px">
              <label>تلفن مستقیم این مکان یا شعبه (اختیاری)</label>
              <input name="locations[{{ $idx }}][phone]" value="{{ $loc['phone'] ?? '' }}" placeholder="مثال: 02128421075 (به همراه کد شهرستان)">
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
          <div class="note">فرمت‌های مجاز: PNG, JPG (حداکثر ۴ مگابایت)</div>
        </div>

        <div class="field">
          <label>تصویر بنر کاور بالای صفحه</label>
          @if($company->cover)
            <div style="margin-bottom:6px"><img src="{{ asset($company->cover) }}" height="50" style="border-radius:6px;border:1px solid var(--border)"></div>
          @endif
          <input type="file" name="cover" accept="image/png,image/jpeg,image/webp">
          <div class="note">نمایش در پس‌زمینه بالای پروفایل شرکت</div>
        </div>
      </div>

      <div class="form-row" style="margin-top:10px">
        <div class="field">
          <label>📄 کاتالوگ محصولات و خدمات (فایل PDF)</label>
          @if($company->catalog)
            <div style="margin-bottom:6px"><a href="{{ asset($company->catalog) }}" target="_blank" style="font-weight:700">مشاهده کاتالوگ فعلی</a></div>
          @endif
          <input type="file" name="catalog" accept="application/pdf">
          <div class="note">فرمت مجاز: PDF (حداکثر ۲۵ مگابایت)</div>
        </div>

        <div class="field">
          <label>📸 گالری تصاویر محصولات یا کارخانه (انتخاب چندتایی)</label>
          @if($company->gallery)
            <div style="display:flex;gap:6px;margin-bottom:6px;flex-wrap:wrap">
              @foreach((array)$company->gallery as $img)
                <img src="{{ asset($img) }}" height="40" style="border-radius:4px;border:1px solid var(--border)">
              @endforeach
            </div>
          @endif
          <input type="file" name="gallery[]" multiple accept="image/png,image/jpeg,image/webp">
          <div class="note">می‌توانید هم‌زمان چند عکس انتخاب کنید.</div>
        </div>
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
let nextLocIndex = {{ count($locList) }};

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
  cards.forEach((card, i) => {
    const numEl = card.querySelector('.loc-number');
    if (numEl) numEl.textContent = (i + 1);
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
        <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
          <span class="badge" style="background:#e0e7ff;color:#3730a3;font-weight:700">
            📍 موقعیت شماره <span class="loc-number"></span>
          </span>
          <label style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;font-size:13px;color:var(--text);user-select:none">
            <input type="radio" name="primary_location_index" value="${idx}">
            <span><b>آدرس و شهر اصلی دایرکتوری</b></span>
          </label>
        </div>
        <button type="button" class="btn-remove-loc" onclick="removeLocationBlock('${idx}')">
          🗑️ حذف این آدرس
        </button>
      </div>

      <div class="form-row">
        <div class="field" style="flex:1">
          <label>نوع مکان / ماهیت آدرس <span style="color:red">*</span></label>
          <select name="locations[${idx}][type]" required>
            ${typeOptions}
          </select>
        </div>
        <div class="field" style="flex:1">
          <label>عنوان یا نام اختصاصی این مکان (اختیاری)</label>
          <input name="locations[${idx}][title]" placeholder="مثال: شعبه ظهیرالاسلام، کارخانه شماره ۱، انبار کهریزک">
        </div>
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

      <div class="field" style="margin-bottom:0;margin-top:10px">
        <label>تلفن مستقیم این مکان یا شعبه (اختیاری)</label>
        <input name="locations[${idx}][phone]" placeholder="مثال: 02128421075 (به همراه کد شهرستان)">
      </div>
    </div>
  `;

  container.insertAdjacentHTML('beforeend', cardHtml);
  updateLocationNumbers();
  initLocationCascade(idx);

  // Smooth scroll into view
  const newCard = document.getElementById('loc_card_' + idx);
  if (newCard) {
    newCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }
}

function toggleServices() {
  const carton = document.getElementById('act_carton')?.checked;
  const print = document.getElementById('act_print')?.checked;
  const postPress = document.getElementById('act_post_press')?.checked;
  const machine = document.getElementById('act_machine')?.checked;
  const machineSrv = document.getElementById('act_machine_srv')?.checked;
  const raw = document.getElementById('act_raw')?.checked;

  const sec = document.getElementById('services_section');
  const boxCarton = document.getElementById('group_carton');
  const boxPrint = document.getElementById('group_printing');
  const boxPostPress = document.getElementById('group_post_press');
  const boxPrintProducts = document.getElementById('group_print_products');
  const boxMachine = document.getElementById('group_machinery');
  const boxMachineSrv = document.getElementById('group_machine_services');

  if (sec) {
    sec.style.display = (carton || print || postPress || machine || machineSrv || raw) ? 'block' : 'none';
  }
  if (boxCarton) boxCarton.style.display = carton ? 'block' : 'none';
  if (boxPrint) boxPrint.style.display = print ? 'block' : 'none';
  if (boxPostPress) boxPostPress.style.display = (postPress || print) ? 'block' : 'none';
  if (boxPrintProducts) boxPrintProducts.style.display = (print || carton) ? 'block' : 'none';
  if (boxMachine) boxMachine.style.display = machine ? 'block' : 'none';
  if (boxMachineSrv) boxMachineSrv.style.display = machineSrv ? 'block' : 'none';
}

document.addEventListener('DOMContentLoaded', function() {
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
});
</script>
@endsection
