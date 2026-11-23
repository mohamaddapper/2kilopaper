@extends('layouts.app', ['title' => $company->exists ? 'ویرایش شرکت' : 'ثبت شرکت'])

@section('content')
<div style="max-width:920px;margin:20px auto">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
    <h2 style="margin:0;font-size:22px;color:var(--primary)">{{ $company->exists ? 'ویرایش شرکت' : 'ثبت نام و اطلاعات شرکت' }}</h2>
    <a href="{{ route('dashboard') }}" class="btn ghost small">بازگشت به پنل من</a>
  </div>

  @if($errors->any())
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

      <div class="form-row">
        <div class="field">
          <label>دسته‌بندی اصلی در دایرکتوری <span style="color:red">*</span></label>
          <select name="category_id" required>
            <option value="">-- انتخاب دسته‌بندی --</option>
            @foreach($categories as $p)
              <optgroup label="{{ $p->name }}">
                @foreach($p->children as $ch)
                  <option value="{{ $ch->id }}" @selected($f('category_id') == $ch->id)>{{ $ch->name }}</option>
                @endforeach
              </optgroup>
            @endforeach
          </select>
        </div>

        <div class="field">
          <label>شهر اصلی دایرکتوری <span style="color:red">*</span></label>
          <select name="city_id" required>
            <option value="">-- انتخاب شهر --</option>
            @foreach($cities as $c)
              <option value="{{ $c->id }}" @selected($f('city_id') == $c->id)>{{ $c->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="field" style="margin-top:10px">
        <label><b>حوزه‌های فعالیت شرکت:</b> (می‌توانید چند مورد را انتخاب کنید تا گزینه‌های تخصصی آن‌ها باز شود)</label>
        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:8px">
          <label class="activity-pill">
            <input type="checkbox" name="activity_types[]" value="carton_maker" id="act_carton" @checked($hasAct('carton_maker')) onchange="toggleServices()">
            <span>تولیدکننده جعبه و کارتن</span>
          </label>
          <label class="activity-pill">
            <input type="checkbox" name="activity_types[]" value="machinery" id="act_machine" @checked($hasAct('machinery')) onchange="toggleServices()">
            <span>فروشنده، تولید یا واردکننده ماشین‌آلات</span>
          </label>
          <label class="activity-pill">
            <input type="checkbox" name="activity_types[]" value="printing" id="act_print" @checked($hasAct('printing')) onchange="toggleServices()">
            <span>خدمات چاپ و چاپخانه</span>
          </label>
          <label class="activity-pill">
            <input type="checkbox" name="activity_types[]" value="raw_material" id="act_raw" @checked($hasAct('raw_material')) onchange="toggleServices()">
            <span>تأمین‌کننده کاغذ، مقوا و آخال</span>
          </label>
        </div>
      </div>
    </div>

    <!-- بخش ۲: محصولات و خدمات تخصصی شرطی -->
    <div class="form-card" id="services_section" style="display:none">
      <h3>
        <span>۲. محصولات و خدمات تخصصی</span>
        <span class="badge" style="background:#eef3f9;color:var(--primary)">هوشمند و انتخابی</span>
      </h3>

      <!-- گروه کارتن‌سازی -->
      <div id="group_carton" class="dynamic-box">
        <label style="font-weight:700;display:block;margin-bottom:8px;color:#1e3a8a">📦 خدمات و محصولات جعبه و کارتن‌سازی:</label>
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

      <!-- گروه ماشین‌آلات -->
      <div id="group_machinery" class="dynamic-box" style="margin-top:16px">
        <label style="font-weight:700;display:block;margin-bottom:8px;color:#065f46">⚙️ ماشین‌آلات و تجهیزات ورق‌سازی و چاپ:</label>
        <div class="check-grid">
          @php
            $machineItems = [
              'پری هیتر', 'تری پری هیتر', 'پری کاندیشنر', 'رول گیر کاغذ', 'ترمز سینگل',
              'سینگل فیسر', 'دستگاه چسب زن', 'دابل فیسر', 'اسلیتر اسکورر', 'کاتاف الکترونیکی',
              'دستگاه کاتاف دوبل', 'دستگاه کاتاف هلیکال', 'دستگاه استاکر', 'استاکر دوبل', 'سیلندر کروگیت'
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

      <!-- گروه چاپخانه -->
      <div id="group_printing" class="dynamic-box" style="margin-top:16px">
        <label style="font-weight:700;display:block;margin-bottom:8px;color:#7c2d12">🖨️ خدمات و تجهیزات چاپخانه:</label>
        <div class="check-grid">
          @php
            $printItems = [
              'آتلیه طراحی', 'فاقد چاپخانه',
              'چاپ افست ۱ ورقی', 'چاپ افست ۲ ورقی', 'چاپ افست ۲/۵ ورقی', 'چاپ افست ۴/۵ ورقی', 'چاپ افست ۶ ورقی', 'چاپ افست ۹ ورقی',
              'چاپ افست ۱ رنگ', 'چاپ افست ۲ رنگ', 'چاپ افست ۴ رنگ', 'چاپ افست ۵ رنگ', 'چاپ افست ۶ رنگ', 'چاپ افست ۷ رنگ', 'چاپ افست ۸ رنگ'
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
    </div>

    <!-- بخش ۳: موقعیت‌های مکانی (کارخانه و دفتر) -->
    <div class="form-card">
      <h3>
        <span>۳. اطلاعات مکانی و آدرس‌ها</span>
        <span class="badge" style="background:#f4f6f9">تفکیک کارخانه و دفتر</span>
      </h3>

      <div style="background:#fbfcfe;border:1px solid var(--border);border-radius:10px;padding:16px;margin-bottom:14px">
        <h4 style="margin:0 0 10px;font-size:15px;color:var(--text)">🏭 مشخصات کارخانه / کارگاه:</h4>
        <div class="form-row">
          <div class="field">
            <label>استان کارخانه</label>
            <select name="factory_province">
              <option value="">-- انتخاب استان --</option>
              @foreach($provinces as $pr)
                <option value="{{ $pr }}" @selected($f('factory_province') == $pr)>{{ $pr }}</option>
              @endforeach
            </select>
          </div>
          <div class="field">
            <label>شهرستان / شهرک صنعتی کارخانه</label>
            <input name="factory_city" value="{{ $f('factory_city') }}" placeholder="مثال: پیرانشهر، شهرک صنعتی">
          </div>
        </div>
        <div class="field" style="margin-bottom:0">
          <label>آدرس دقیق کارخانه</label>
          <input name="factory_address" value="{{ $f('factory_address') }}" placeholder="مثال: شهرک صنعتی، فاز ۲، خیابان تلاش، پلاک ۱۲">
        </div>
      </div>

      <div style="background:#fbfcfe;border:1px solid var(--border);border-radius:10px;padding:16px">
        <h4 style="margin:0 0 10px;font-size:15px;color:var(--text)">🏢 مشخصات دفتر مرکزی / فروشگاه (اختیاری):</h4>
        <div class="form-row">
          <div class="field">
            <label>استان دفتر</label>
            <select name="office_province">
              <option value="">-- انتخاب استان --</option>
              @foreach($provinces as $pr)
                <option value="{{ $pr }}" @selected($f('office_province') == $pr)>{{ $pr }}</option>
              @endforeach
            </select>
          </div>
          <div class="field">
            <label>شهر دفتر مرکزی</label>
            <input name="office_city" value="{{ $f('office_city') }}" placeholder="مثال: ارومیه">
          </div>
        </div>
        <div class="field" style="margin-bottom:0">
          <label>آدرس دقیق دفتر</label>
          <input name="office_address" value="{{ $f('office_address') }}" placeholder="مثال: خیابان حسنی، مجتمع پزشکان، طبقه ۱۲">
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
          <label>شماره تلفن ثابت اصلی <span style="color:red">*</span></label>
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

      <div class="field" style="margin-top:10px">
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
function toggleServices() {
  const carton = document.getElementById('act_carton').checked;
  const machine = document.getElementById('act_machine').checked;
  const print = document.getElementById('act_print').checked;

  const sec = document.getElementById('services_section');
  const boxCarton = document.getElementById('group_carton');
  const boxMachine = document.getElementById('group_machinery');
  const boxPrint = document.getElementById('group_printing');

  if (carton || machine || print) {
    sec.style.display = 'block';
  } else {
    sec.style.display = 'none';
  }

  boxCarton.style.display = carton ? 'block' : 'none';
  boxMachine.style.display = machine ? 'block' : 'none';
  boxPrint.style.display = print ? 'block' : 'none';
}

document.addEventListener('DOMContentLoaded', function() {
  toggleServices();
});
</script>
@endsection
