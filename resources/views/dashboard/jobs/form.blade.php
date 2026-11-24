@extends('layouts.app', ['title' => $job->exists ? 'ویرایش آگهی استخدام' : 'درج آگهی استخدام جدید', 'dashboardNav' => true])

@section('content')
<div style="max-width:960px;margin:24px auto 48px">
  
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
    <div>
      <h1 style="margin:0 0 6px;font-size:22px;color:var(--text)">
        {{ $job->exists ? '✏️ ویرایش آگهی استخدام' : '📢 درج آگهی استخدام جدید' }}
      </h1>
      <p style="margin:0;color:var(--muted);font-size:13.5px">اطلاعات شغل، شرایط کارخانه یا دفتر و تسهیلات رفاهی را مشخص کنید.</p>
    </div>
    <a href="{{ route('dashboard') }}#jobs" class="btn ghost small">بازگشت به پنل</a>
  </div>

  @if($errors->any())
    <div class="card" style="border-color:#ef4444;background:#fef2f2;color:#991b1b;margin-bottom:20px;padding:14px 18px">
      <b style="display:block;margin-bottom:6px">لطفاً موارد زیر را اصلاح فرمایید:</b>
      <ul style="margin:0;padding-right:20px;font-size:13.5px">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="post" action="{{ $job->exists ? route('employer.jobs.update', $job) : route('employer.jobs.store') }}" style="display:flex;flex-direction:column;gap:20px">
    @csrf
    @if($job->exists)
      @method('PUT')
    @endif

    <!-- بخش ۱: اطلاعات پایه آگهی -->
    <div class="card" style="background:#fff;border-radius:12px;padding:22px">
      <h3 style="margin:0 0 16px;font-size:16px;color:var(--primary);border-bottom:1px solid var(--border);padding-bottom:10px">
        ۱. عنوان و هویت کارفرما
      </h3>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
        <div>
          <label style="display:block;margin-bottom:6px;font-size:13px;font-weight:600">
            عنوان آگهی شغلی <span style="color:red">*</span>
          </label>
          <input name="title" value="{{ old('title', $job->title) }}" placeholder="مثال: اپراتور چاپ فلکسو، کارشناس فروش کارتن..." required style="width:100%;border:1px solid var(--border)">
        </div>

        <div>
          <label style="display:block;margin-bottom:6px;font-size:13px;font-weight:600">شرکت کارفرما (اختیاری)</label>
          <select name="company_id" style="width:100%">
            <option value="">-- بدون انتساب به شرکت (آگهی مستقل) --</option>
            @foreach($companies as $c)
              <option value="{{ $c->id }}" @selected(old('company_id', $job->company_id) == $c->id)>
                {{ $c->name }}
              </option>
            @endforeach
          </select>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px">
        <div>
          <label style="display:block;margin-bottom:6px;font-size:13px;font-weight:600">دسته‌بندی تخصصی</label>
          <select name="category_id" style="width:100%">
            <option value="">انتخاب زمینه فعالیت...</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" @selected(old('category_id', $job->category_id) == $cat->id)>{{ $cat->name }}</option>
              @foreach($cat->children as $child)
                <option value="{{ $child->id }}" @selected(old('category_id', $job->category_id) == $child->id)>— {{ $child->name }}</option>
              @endforeach
            @endforeach
          </select>
        </div>

        <div>
          <label style="display:block;margin-bottom:6px;font-size:13px;font-weight:600">نوع همکاری <span style="color:red">*</span></label>
          <select name="job_type" required style="width:100%">
            <option value="full_time" @selected(old('job_type', $job->job_type) == 'full_time')>تمام‌وقت</option>
            <option value="part_time" @selected(old('job_type', $job->job_type) == 'part_time')>پاره‌وقت</option>
            <option value="shift" @selected(old('job_type', $job->job_type) == 'shift')>شیفتی / نوبت‌کاری</option>
            <option value="project" @selected(old('job_type', $job->job_type) == 'project')>پروژه‌ای</option>
            <option value="internship" @selected(old('job_type', $job->job_type) == 'internship')>کارآموزی</option>
          </select>
        </div>

        <div>
          <label style="display:block;margin-bottom:6px;font-size:13px;font-weight:600">شیفت کاری</label>
          <select name="work_shift" style="width:100%">
            <option value="">عادی / مشخص نیست</option>
            <option value="day" @selected(old('work_shift', $job->work_shift) == 'day')>تک شیفت روز</option>
            <option value="rotational" @selected(old('work_shift', $job->work_shift) == 'rotational')>دو شیفت چرخشی</option>
            <option value="night" @selected(old('work_shift', $job->work_shift) == 'night')>شیفت شب</option>
            <option value="shift" @selected(old('work_shift', $job->work_shift) == 'shift')>نوبت‌کاری گردشی</option>
          </select>
        </div>
      </div>

      <!-- نشان آگهی فوری -->
      <div style="margin-top:16px;padding-top:14px;border-top:1px solid #f1f5f9">
        <label style="display:inline-flex;align-items:center;gap:10px;cursor:pointer;padding:10px 16px;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;user-select:none">
          <input type="checkbox" name="is_immediate" value="1" @checked(old('is_immediate', $job->is_immediate)) style="width:18px!important;height:18px!important;accent-color:#d97706;cursor:pointer;margin:0">
          <div>
            <span style="font-size:13.5px;font-weight:700;color:#92400e;display:block">⚡ نیاز فوری به جذب نیرو (نشان آگهی فوری)</span>
            <span style="font-size:12px;color:#b45309;display:block;margin-top:2px">این آگهی با برچسب برجسته قرمز «فوری» در صدر فرصت‌های شغلی نمایش داده می‌شود.</span>
          </div>
        </label>
      </div>
    </div>

    <!-- بخش ۲: محل کار و موقعیت جغرافیایی -->
    <div class="card" style="background:#fff;border-radius:12px;padding:22px">
      <h3 style="margin:0 0 16px;font-size:16px;color:var(--primary);border-bottom:1px solid var(--border);padding-bottom:10px">
        ۲. محل کار و موقعیت جغرافیایی
      </h3>

      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px">
        <div>
          <label style="display:block;margin-bottom:6px;font-size:13px;font-weight:600">نوع محل کار <span style="color:red">*</span></label>
          <select name="workplace_type" required style="width:100%">
            <option value="factory" @selected(old('workplace_type', $job->workplace_type) == 'factory')>کارخانه / کارگاه</option>
            <option value="office" @selected(old('workplace_type', $job->workplace_type) == 'office')>دفتر مرکزی / اداری</option>
            <option value="store" @selected(old('workplace_type', $job->workplace_type) == 'store')>فروشگاه / انبار</option>
          </select>
        </div>

        <div>
          <label style="display:block;margin-bottom:6px;font-size:13px;font-weight:600">استان</label>
          <select name="province_id" id="job_province" style="width:100%">
            <option value="">انتخاب استان...</option>
            @foreach($provinces as $p)
              <option value="{{ $p->id }}" @selected(old('province_id', $job->province_id) == $p->id)>{{ $p->name }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label style="display:block;margin-bottom:6px;font-size:13px;font-weight:600">شهر / شهرستان</label>
          <select name="city_id" id="job_city" style="width:100%">
            <option value="">ابتدا استان را انتخاب کنید</option>
          </select>
        </div>
      </div>

      <div>
        <label style="display:block;margin-bottom:6px;font-size:13px;font-weight:600">آدرس دقیق یا نام شهرک صنعتی</label>
        <input name="address" value="{{ old('address', $job->address) }}" placeholder="مثال: شهرک صنعتی شمس‌آباد، فاز ۲، بلوار تلاش، پلاک ۱۲" style="width:100%;border:1px solid var(--border)">
      </div>
    </div>

    <!-- بخش ۳: حقوق، مزایا و تسهیلات کارخانه -->
    <div class="card" style="background:#fff;border-radius:12px;padding:22px">
      <h3 style="margin:0 0 16px;font-size:16px;color:var(--primary);border-bottom:1px solid var(--border);padding-bottom:10px">
        ۳. حقوق، مزایا و تسهیلات رفاهی
      </h3>

      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:18px">
        <div>
          <label style="display:block;margin-bottom:6px;font-size:13px;font-weight:600">نوع حقوق پیشنهادی <span style="color:red">*</span></label>
          <select name="salary_type" id="salary_type_select" required style="width:100%">
            <option value="negotiable" @selected(old('salary_type', $job->salary_type) == 'negotiable')>توافقی</option>
            <option value="ministry_plus" @selected(old('salary_type', $job->salary_type) == 'ministry_plus')>قانون کار + پاداش تولید</option>
            <option value="range" @selected(old('salary_type', $job->salary_type) == 'range')>بازه عددی مشخص (تومان)</option>
          </select>
        </div>

        <div id="salary_min_box">
          <label style="display:block;margin-bottom:6px;font-size:13px;font-weight:600">حداقل حقوق (تومان)</label>
          <input type="text" inputmode="numeric" name="salary_min" id="salary_min_input" class="salary-number-format"
                 value="{{ old('salary_min', $job->salary_min ? number_format((int)$job->salary_min) : '') }}"
                 placeholder="مثال: ۱۵,۰۰۰,۰۰۰"
                 autocomplete="off"
                 style="width:100%;border:1px solid var(--border);padding:9px 12px;font-size:14px;border-radius:8px">
          <span class="salary-helper" id="salary_min_helper" style="font-size:11.5px;color:var(--muted);display:block;margin-top:4px"></span>
        </div>

        <div id="salary_max_box">
          <label style="display:block;margin-bottom:6px;font-size:13px;font-weight:600">حداکثر حقوق (تومان)</label>
          <input type="text" inputmode="numeric" name="salary_max" id="salary_max_input" class="salary-number-format"
                 value="{{ old('salary_max', $job->salary_max ? number_format((int)$job->salary_max) : '') }}"
                 placeholder="مثال: ۲۵,۰۰۰,۰۰۰"
                 autocomplete="off"
                 style="width:100%;border:1px solid var(--border);padding:9px 12px;font-size:14px;border-radius:8px">
          <span class="salary-helper" id="salary_max_helper" style="font-size:11.5px;color:var(--muted);display:block;margin-top:4px"></span>
        </div>
      </div>

      <!-- امکانات رفاهی کارخانه‌ای -->
      <label style="display:block;margin-bottom:10px;font-size:13.5px;font-weight:700;color:var(--text)">
        امکانات و مزایای ویژه (تاثیرگذار در جذب نیروهای صنعتی):
      </label>

      @php
        $benefits = $job->benefits ?? [];
      @endphp
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:12px;background:#fbfcfe;border:1px solid var(--border);border-radius:10px;padding:16px">
        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 14px;background:#fff;border:1px solid #e2e8f0;border-radius:8px;user-select:none">
          <input type="checkbox" name="benefits[has_service]" id="chk_service" value="1" @checked(!empty($benefits['has_service'])) style="width:18px;height:18px;accent-color:var(--primary);flex-shrink:0;cursor:pointer">
          <span style="font-size:13.5px;font-weight:600;white-space:nowrap;color:#1e293b">🚌 سرویس رفت‌وآمد</span>
        </label>

        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 14px;background:#fff;border:1px solid #e2e8f0;border-radius:8px;user-select:none">
          <input type="checkbox" name="benefits[has_meal]" value="1" @checked(!empty($benefits['has_meal'])) style="width:18px;height:18px;accent-color:var(--primary);flex-shrink:0;cursor:pointer">
          <span style="font-size:13.5px;font-weight:600;white-space:nowrap;color:#1e293b">🍽️ وعده غذایی (ناهار / صبحانه)</span>
        </label>

        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 14px;background:#fff;border:1px solid #e2e8f0;border-radius:8px;user-select:none">
          <input type="checkbox" name="benefits[has_accommodation]" value="1" @checked(!empty($benefits['has_accommodation'])) style="width:18px;height:18px;accent-color:var(--primary);flex-shrink:0;cursor:pointer">
          <span style="font-size:13.5px;font-weight:600;white-space:nowrap;color:#1e293b">🛏️ خوابگاه / اسکان پرسنل</span>
        </label>

        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 14px;background:#fff;border:1px solid #e2e8f0;border-radius:8px;user-select:none">
          <input type="checkbox" name="benefits[has_insurance]" value="1" @checked(!empty($benefits['has_insurance']) || !$job->exists) style="width:18px;height:18px;accent-color:var(--primary);flex-shrink:0;cursor:pointer">
          <span style="font-size:13.5px;font-weight:600;white-space:nowrap;color:#1e293b">🛡️ بیمه تأمین اجتماعی</span>
        </label>

        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 14px;background:#fff;border:1px solid #e2e8f0;border-radius:8px;user-select:none">
          <input type="checkbox" name="benefits[has_supplementary_insurance]" value="1" @checked(!empty($benefits['has_supplementary_insurance'])) style="width:18px;height:18px;accent-color:var(--primary);flex-shrink:0;cursor:pointer">
          <span style="font-size:13.5px;font-weight:600;white-space:nowrap;color:#1e293b">🏥 بیمه تکمیلی درمان</span>
        </label>

        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 14px;background:#fff;border:1px solid #e2e8f0;border-radius:8px;user-select:none">
          <input type="checkbox" name="benefits[has_bonus]" value="1" @checked(!empty($benefits['has_bonus'])) style="width:18px;height:18px;accent-color:var(--primary);flex-shrink:0;cursor:pointer">
          <span style="font-size:13.5px;font-weight:600;white-space:nowrap;color:#1e293b">💰 پاداش تولید / آکورد / پورسانت</span>
        </label>
      </div>

      <div id="service_routes_row" style="margin-top:12px;display:{{ !empty($benefits['has_service']) ? 'block' : 'none' }}">
        <label style="display:block;margin-bottom:6px;font-size:13px">مسیرهای سرویس ایاب و ذهاب:</label>
        <input name="benefits[service_routes]" value="{{ $benefits['service_routes'] ?? '' }}" placeholder="مثال: آزادی، اسلامشهر، نسیم‌شهر، رباط کریم، جاده ساوه..." style="width:100%;border:1px solid var(--border)">
      </div>
    </div>

    <!-- بخش ۴: شرایط احراز و مهارت‌های مورد نظر -->
    <div class="card" style="background:#fff;border-radius:12px;padding:22px">
      <h3 style="margin:0 0 16px;font-size:16px;color:var(--primary);border-bottom:1px solid var(--border);padding-bottom:10px">
        ۴. شرایط احراز و مهارت‌های فنی
      </h3>

      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px">
        <div>
          <label style="display:block;margin-bottom:6px;font-size:13px;font-weight:600">سابقه کار مورد نیاز <span style="color:red">*</span></label>
          <select name="experience_years" required style="width:100%">
            <option value="0" @selected(old('experience_years', $job->experience_years) == '0')>بدون نیاز به سابقه کار</option>
            <option value="1-3" @selected(old('experience_years', $job->experience_years) == '1-3')>۱ تا ۳ سال سابقه</option>
            <option value="3-6" @selected(old('experience_years', $job->experience_years) == '3-6')>۳ تا ۶ سال سابقه</option>
            <option value="6+" @selected(old('experience_years', $job->experience_years) == '6+')>بیش از ۶ سال سابقه</option>
          </select>
        </div>

        <div>
          <label style="display:block;margin-bottom:6px;font-size:13px;font-weight:600">جنسیت</label>
          <select name="gender" style="width:100%">
            <option value="any" @selected(old('gender', $job->gender) == 'any')>فرقی ندارد</option>
            <option value="male" @selected(old('gender', $job->gender) == 'male')>فقط آقا</option>
            <option value="female" @selected(old('gender', $job->gender) == 'female')>فقط خانم</option>
          </select>
        </div>

        <div>
          <label style="display:block;margin-bottom:6px;font-size:13px;font-weight:600">وضعیت خدمت سربازی</label>
          <select name="military_service" style="width:100%">
            <option value="not_required" @selected(old('military_service', $job->military_service) == 'not_required')>مهم نیست / فرقی ندارد</option>
            <option value="exempt_or_done" @selected(old('military_service', $job->military_service) == 'exempt_or_done')>کارت پایان خدمت یا معافیت دائم الزامی است</option>
          </select>
        </div>
      </div>

      <div style="margin-bottom:16px">
        <label style="display:block;margin-bottom:6px;font-size:13px;font-weight:600">دستگاه‌ها و مهارت‌های تخصصی (با کاما جدا کنید)</label>
        <input name="skills" value="{{ is_array($job->skills) ? implode('، ', $job->skills) : old('skills', $job->skills) }}" placeholder="مثال: دایکات بوبست، چاپ فلکسو، ورق‌سازی، لمینت اتوماتیک، کنترل کیفیت" style="width:100%;border:1px solid var(--border)">
      </div>

      <div style="margin-bottom:16px">
        <label style="display:block;margin-bottom:6px;font-size:13px;font-weight:600">شرح شغل و وظایف <span style="color:red">*</span></label>
        <textarea name="description" rows="4" required placeholder="توضیح دهید کارجو چه مسئولیت‌ها و کارهایی را انجام خواهد داد..." style="width:100%;border:1px solid var(--border);border-radius:8px;padding:10px">{{ old('description', $job->description) }}</textarea>
      </div>

      <div>
        <label style="display:block;margin-bottom:6px;font-size:13px;font-weight:600">شرایط احراز و ویژگی‌های فردی (اختیاری)</label>
        <textarea name="requirements" rows="3" placeholder="مدرک تحصیلی، دقت، روحیه کار تیمی، توانایی کار در شیفت..." style="width:100%;border:1px solid var(--border);border-radius:8px;padding:10px">{{ old('requirements', $job->requirements) }}</textarea>
      </div>
    </div>

    <!-- بخش ۵: روش ارتباط و دریافت متقاضی -->
    <div class="card" style="background:#fff;border-radius:12px;padding:22px">
      <h3 style="margin:0 0 16px;font-size:16px;color:var(--primary);border-bottom:1px solid var(--border);padding-bottom:10px">
        ۵. نحوه دریافت رزومه و ارتباط با کارجویان
      </h3>

      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px">
        <div>
          <label style="display:block;margin-bottom:6px;font-size:13px;font-weight:600">روش ارتباطی اصلی</label>
          <select name="contact_type" style="width:100%">
            <option value="platform" @selected(old('contact_type', $job->contact_type) == 'platform')>دریافت رزومه در سامانه (پیش‌فرض)</option>
            <option value="direct" @selected(old('contact_type', $job->contact_type) == 'direct')>تماس تلفنی مستقیم / واتساپ (فوری)</option>
            <option value="both" @selected(old('contact_type', $job->contact_type) == 'both')>هر دو حالت (رزومه + تماس مستقیم)</option>
          </select>
        </div>

        <div>
          <label style="display:block;margin-bottom:6px;font-size:13px">تلفن تماس مستقیم مسئول استخدام</label>
          <input name="contact_phone" value="{{ old('contact_phone', $job->contact_phone) }}" placeholder="مثال: 09121234567" style="width:100%;border:1px solid var(--border)">
        </div>

        <div>
          <label style="display:block;margin-bottom:6px;font-size:13px">شماره واتساپ یا بله</label>
          <input name="contact_whatsapp" value="{{ old('contact_whatsapp', $job->contact_whatsapp) }}" placeholder="مثال: 09121234567" style="width:100%;border:1px solid var(--border)">
        </div>
      </div>
    </div>

    <div style="display:flex;gap:12px;align-items:center;margin-top:10px">
      <button type="submit" class="btn" style="padding:12px 36px;font-size:16px;font-weight:700">
        {{ $job->exists ? 'ذخیره تغییرات آگهی' : '🚀 انتشار آگهی استخدام' }}
      </button>
      <a href="{{ route('dashboard') }}#jobs" class="btn ghost">انصراف</a>
    </div>

  </form>
</div>

<script>
const locationsTree = @json($locationsTree);
const currentCityId = '{{ old("city_id", $job->city_id) }}';

function updateCities() {
  const provSelect = document.getElementById('job_province');
  const citySelect = document.getElementById('job_city');
  if (!provSelect || !citySelect) return;

  const provId = provSelect.value;
  citySelect.innerHTML = '<option value="">انتخاب شهر...</option>';

  if (!provId) return;

  const foundProv = locationsTree.find(p => String(p.id) === String(provId));
  if (foundProv && foundProv.cities) {
    foundProv.cities.forEach(city => {
      const opt = document.createElement('option');
      opt.value = city.id;
      opt.textContent = city.name;
      if (String(city.id) === String(currentCityId)) {
        opt.selected = true;
      }
      citySelect.appendChild(opt);
    });
  }
}

document.addEventListener('DOMContentLoaded', function() {
  const provSelect = document.getElementById('job_province');
  if (provSelect) {
    provSelect.addEventListener('change', updateCities);
    if (provSelect.value) {
      updateCities();
    }
  }

  // Toggle service routes field
  const chkService = document.getElementById('chk_service');
  const serviceRow = document.getElementById('service_routes_row');
  if (chkService && serviceRow) {
    chkService.addEventListener('change', function() {
      serviceRow.style.display = this.checked ? 'block' : 'none';
    });
  }

  // Salary formatting (thousands separator & Persian/English digits)
  function cleanToRawDigits(str) {
    if (!str) return '';
    var persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
    var arabic  = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
    for (var i = 0; i < 10; i++) {
      str = str.split(persian[i]).join(i.toString());
      str = str.split(arabic[i]).join(i.toString());
    }
    return str.replace(/\D/g, '');
  }

  function formatCommas(str) {
    var raw = cleanToRawDigits(str);
    if (!raw) return '';
    return raw.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  }

  function updateSalaryHelper(input, helperId) {
    var helper = document.getElementById(helperId);
    if (!helper) return;
    var raw = cleanToRawDigits(input.value);
    if (!raw || parseInt(raw, 10) === 0) {
      helper.textContent = '';
      return;
    }
    var num = parseInt(raw, 10);
    if (num >= 1000000) {
      var millions = (num / 1000000).toLocaleString('fa-IR', { maximumFractionDigits: 2 });
      helper.textContent = 'معادل ' + millions + ' میلیون تومان';
      helper.style.color = '#0284c7';
    } else if (num >= 1000) {
      var thousands = (num / 1000).toLocaleString('fa-IR', { maximumFractionDigits: 1 });
      helper.textContent = 'معادل ' + thousands + ' هزار تومان';
      helper.style.color = 'var(--muted)';
    } else {
      helper.textContent = num.toLocaleString('fa-IR') + ' تومان';
      helper.style.color = 'var(--muted)';
    }
  }

  document.querySelectorAll('.salary-number-format').forEach(function(input) {
    var helperId = input.id === 'salary_min_input' ? 'salary_min_helper' : 'salary_max_helper';
    if (input.value) {
      input.value = formatCommas(input.value);
      updateSalaryHelper(input, helperId);
    }
    input.addEventListener('input', function() {
      this.value = formatCommas(this.value);
      updateSalaryHelper(this, helperId);
    });
  });
});
</script>
@endsection
