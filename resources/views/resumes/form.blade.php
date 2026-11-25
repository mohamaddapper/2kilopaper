@extends('layouts.app', ['title' => $resume->exists ? 'ویرایش رزومه کارجو' : 'ثبت رزومه کارجو در بانک متخصصین'])

@section('content')
<div class="page-wrapper" style="margin:28px 0 56px">
  <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:20px">
    <div>
      <h1 style="margin:0 0 6px;font-size:22px;color:var(--text)">
        {{ $resume->exists ? 'ویرایش رزومه کارجو' : 'ثبت رایگان رزومه در بانک کارجویان صنعت' }}
      </h1>
      <p style="margin:0;font-size:13.5px;color:var(--muted)">
        اطلاعات شما در بانک کارجویان کارخانجات کاغذ، کارتن، چاپ و بسته‌بندی ثبت می‌شود تا مدیران و کارفرمایان با شما تماس بگیرند.
      </p>
    </div>
    <a href="{{ route('dashboard') }}" class="btn ghost small">بازگشت به میز کار</a>
  </div>

  @if($errors->any())
    <div class="notice bad" style="margin-bottom:18px">
      @foreach($errors->all() as $err)
        <div>• {{ $err }}</div>
      @endforeach
    </div>
  @endif

  <form method="POST" action="{{ $resume->exists ? route('resumes.update', $resume) : route('resumes.store') }}" enctype="multipart/form-data">
    @csrf
    @if($resume->exists)
      @method('PUT')
    @endif

    <div class="form-card">
      <h3><span>۱. مشخصات فردی و عنوان شغلی</span></h3>
      <div class="form-row">
        <div class="field">
          <label>نام و نام خانوادگی <span style="color:red">*</span></label>
          <input name="full_name" value="{{ old('full_name', $resume->full_name) }}" required placeholder="مثال: علی محمدی">
        </div>
        <div class="field">
          <label>شماره تماس <span style="color:red">*</span></label>
          <input name="phone" value="{{ old('phone', $resume->phone) }}" required dir="ltr" style="text-align:left" placeholder="09121234567">
          <div class="note">شماره شما برای عموم مخفی است و فقط به کارفرمایان معتبر نمایش داده می‌شود.</div>
        </div>
      </div>

      <div class="form-row" style="margin-top:10px">
        <div class="field">
          <label>عنوان شغلی / مهارت اصلی <span style="color:red">*</span></label>
          <input name="job_title" value="{{ old('job_title', $resume->job_title) }}" required placeholder="مثال: اپراتور خط ورق‌سازی ۵ لایه / کارشناس فروش کارتن">
        </div>
        <div class="field">
          <label>گروه تخصصی در صنعت</label>
          <select name="specialty">
            <option value="">انتخاب کنید...</option>
            @foreach(\App\Models\Resume::SPECIALTIES as $sp)
              <option value="{{ $sp }}" @selected(old('specialty', $resume->specialty) === $sp)>{{ $sp }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>

    <div class="form-card">
      <h3><span>۲. موقعیت مکانی و شرایط همکاری</span></h3>
      <div class="form-row">
        <div class="field">
          <label>استان <span style="color:red">*</span></label>
          <select name="province_id" id="resume_province_id" required onchange="syncResumeCities()">
            <option value="">انتخاب استان...</option>
            @foreach($provinces as $prov)
              <option value="{{ $prov->id }}" @selected(old('province_id', $resume->province_id) == $prov->id)>{{ $prov->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="field">
          <label>شهر</label>
          <select name="city_id" id="resume_city_id">
            <option value="">انتخاب شهر...</option>
          </select>
        </div>
      </div>

      <div class="form-row-3" style="margin-top:10px">
        <div class="field">
          <label>سابقه کار مرتبط (سال)</label>
          <input type="number" min="0" max="60" name="experience_years" value="{{ old('experience_years', $resume->experience_years ?? 0) }}">
        </div>
        <div class="field">
          <label>نوع همکاری</label>
          <select name="work_type">
            @foreach(\App\Models\Resume::WORK_TYPES as $wtKey => $wtLabel)
              <option value="{{ $wtKey }}" @selected(old('work_type', $resume->work_type ?? 'full_time') === $wtKey)>{{ $wtLabel }}</option>
            @endforeach
          </select>
        </div>
        <div class="field">
          <label>حقوق درخواستی (اختیاری)</label>
          <input name="expected_salary" value="{{ old('expected_salary', $resume->expected_salary) }}" placeholder="مثال: توافقی / ۲۵ میلیون تومان">
        </div>
      </div>

      <div style="margin-top:10px">
        <label class="check-item" style="display:inline-flex">
          <input type="hidden" name="needs_accommodation" value="0">
          <input type="checkbox" name="needs_accommodation" value="1" @checked(old('needs_accommodation', $resume->needs_accommodation))>
          <span>در صورت اشتغال در شهرک‌های صنعتی یا شهر دیگر، نیاز به خوابگاه / اسکان دارم</span>
        </label>
      </div>
    </div>

    <div class="form-card">
      <h3><span>۳. تسلط بر دستگاه‌ها، سوابق و فایل رزومه</span></h3>
      <div class="field">
        <label style="margin-bottom:8px;display:block">دستگاه‌ها و مهارت‌هایی که به آن‌ها تسلط دارید:</label>
        @php $selectedMachines = (array) old('machines', $resume->machines ?? []); @endphp
        <div class="check-grid">
          @foreach(\App\Models\Resume::MACHINE_SKILLS as $skill)
            <label class="check-item">
              <input type="checkbox" name="machines[]" value="{{ $skill }}" @checked(in_array($skill, $selectedMachines, true))>
              <span>{{ $skill }}</span>
            </label>
          @endforeach
        </div>
      </div>

      <div class="field" style="margin-top:14px">
        <label>شرح سوابق کاری و توانمندی‌ها</label>
        <textarea name="bio" rows="5" placeholder="توضیح دهید در کدام کارخانه‌ها یا چاپخانه‌ها کار کرده‌اید، با چه مدل دستگاه‌هایی مسلط هستید و چه توانایی‌هایی دارید...">{{ old('bio', $resume->bio) }}</textarea>
      </div>

      <div class="field" style="margin-top:10px;margin-bottom:0">
        <label>آپلود فایل رزومه PDF (اختیاری)</label>
        @if($resume->resume_file)
          <div style="margin-bottom:6px"><a href="{{ asset($resume->resume_file) }}" target="_blank" style="font-weight:700">مشاهده فایل فعلی</a></div>
        @endif
        <input type="file" name="resume_file" accept="application/pdf">
        <div class="note">اجباری نیست؛ در صورت داشتن فایل PDF می‌توانید بارگذاری کنید (حداکثر ۵ مگابایت).</div>
      </div>
    </div>

    <div style="display:flex;gap:10px;justify-content:flex-end">
      <a href="{{ route('dashboard') }}" class="btn ghost">انصراف</a>
      <button type="submit" class="btn">{{ $resume->exists ? 'ذخیره تغییرات رزومه' : 'ثبت و ارسال رزومه' }}</button>
    </div>
  </form>
</div>

<script>
const RESUME_PROVINCES = @json($provinces);
const INITIAL_CITY_ID = @json((string) old('city_id', $resume->city_id ?? ''));

function syncResumeCities() {
  const provId = document.getElementById('resume_province_id').value;
  const citySelect = document.getElementById('resume_city_id');
  citySelect.innerHTML = '<option value="">انتخاب شهر...</option>';
  const prov = RESUME_PROVINCES.find(p => String(p.id) === String(provId));
  if (!prov || !Array.isArray(prov.cities)) return;
  prov.cities.forEach(c => {
    const opt = document.createElement('option');
    opt.value = c.id;
    opt.textContent = c.name;
    if (String(c.id) === String(INITIAL_CITY_ID)) opt.selected = true;
    citySelect.appendChild(opt);
  });
}
document.addEventListener('DOMContentLoaded', syncResumeCities);
</script>
@endsection
