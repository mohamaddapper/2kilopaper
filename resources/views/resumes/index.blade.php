@extends('layouts.app', ['title' => 'بانک کارجویان و رزومه‌های صنعت کاغذ و کارتن'])

@section('content')
<div class="page-wrapper" style="margin:24px 0 52px">
  <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:14px;margin-bottom:20px">
    <div>
      <h1 style="margin:0 0 6px;font-size:23px;color:var(--text)">{!! svg_icon('users', 20) !!} بانک کارجویان و متخصصین صنعت</h1>
      <p style="margin:0;font-size:13.5px;color:var(--muted)">اپراتورهای دستگاه، کارشناسان فروش، مدیران تولید و نیروهای فنی صنعت کاغذ، کارتن، چاپ و بسته‌بندی</p>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap">
      <a href="{{ route('jobs.index') }}" class="btn ghost small">فرصت‌های شغلی کارخانه‌ها</a>
      <a href="{{ route('resumes.create') }}" class="btn small">+ ثبت رایگان رزومه کارجو</a>
    </div>
  </div>

  <!-- فیلتر جستجوی کارجویان -->
  <form method="GET" action="{{ route('resumes.index') }}" class="card" style="padding:16px;margin-bottom:22px">
    <div style="display:grid;grid-template-columns:2fr 1.5fr 1fr 1fr auto;gap:10px;align-items:center" class="resume-filter-grid">
      <input name="q" value="{{ request('q') }}" placeholder="جستجو در عنوان شغلی، نام یا مهارت...">
      <select name="specialty">
        <option value="">همه تخصص‌ها</option>
        @foreach($specialties as $sp)
          <option value="{{ $sp }}" @selected(request('specialty') === $sp)>{{ $sp }}</option>
        @endforeach
      </select>
      <select name="province_id">
        <option value="">همه استان‌ها</option>
        @foreach($provinces as $prov)
          <option value="{{ $prov->id }}" @selected(request('province_id') == $prov->id)>{{ $prov->name }}</option>
        @endforeach
      </select>
      <select name="min_exp">
        <option value="">سابقه کار (همه)</option>
        <option value="1" @selected(request('min_exp') == '1')>حداقل ۱ سال</option>
        <option value="3" @selected(request('min_exp') == '3')>حداقل ۳ سال</option>
        <option value="5" @selected(request('min_exp') == '5')>حداقل ۵ سال</option>
        <option value="10" @selected(request('min_exp') == '10')>حداقل ۱۰ سال</option>
      </select>
      <button type="submit" class="btn small">جستجو</button>
    </div>
  </form>

  <!-- لیست کارت‌های رزومه -->
  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(310px,1fr));gap:16px">
    @forelse($resumes as $resume)
      <a href="{{ route('resumes.show', $resume) }}" class="card" style="padding:18px;text-decoration:none;color:inherit;display:flex;flex-direction:column;justify-content:space-between;transition:transform .2s">
        <div>
          <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:10px;margin-bottom:8px">
            <h2 style="margin:0;font-size:15.5px;font-weight:800;color:var(--text)">{{ $resume->job_title }}</h2>
            <span class="badge ok" style="font-size:11px;white-space:nowrap">
              {{ $resume->experience_years > 0 ? $resume->experience_years . ' سال سابقه' : 'آماده به کار' }}
            </span>
          </div>

          <div style="font-size:13px;color:var(--primary);font-weight:700;margin-bottom:8px">
            {{ $resume->full_name }}
          </div>

          <div style="font-size:12.5px;color:var(--muted);margin-bottom:10px;display:flex;gap:10px;flex-wrap:wrap">
            <span>{!! svg_icon('map-pin', 14) !!} {{ $resume->province->name ?? 'ایران' }}{{ $resume->city ? ' · ' . $resume->city->name : '' }}</span>
            <span>· {{ $resume->workTypeLabel() }}</span>
            @if($resume->needs_accommodation)
              <span style="color:#d97706">· نیاز به اسکان</span>
            @endif
          </div>

          @if(!empty($resume->machines))
            <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:10px">
              @foreach(array_slice((array)$resume->machines, 0, 3) as $m)
                <span class="badge" style="font-size:11px;background:#eff6ff;color:#0369a1">{{ $m }}</span>
              @endforeach
            </div>
          @endif
        </div>

        <div style="padding-top:10px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;font-size:12px;color:var(--muted)">
          <span>حقوق: {{ $resume->expected_salary ?: 'توافقی' }}</span>
          <span style="color:var(--primary);font-weight:700">مشاهده رزومه ←</span>
        </div>
      </a>
    @empty
      <div class="card" style="grid-column:1/-1;padding:40px;text-align:center;color:var(--muted)">
        <p style="margin:0 0 14px;font-size:14.5px">هنوز رزومه‌ای با این مشخصات ثبت نشده است.</p>
        <a href="{{ route('resumes.create') }}" class="btn small">+ ثبت اولین رزومه کارجو</a>
      </div>
    @endforelse
  </div>

  <div style="margin-top:22px">
    {{ $resumes->links() }}
  </div>
</div>

<style>
@media(max-width:860px){ .resume-filter-grid { grid-template-columns: 1fr 1fr !important; } }
@media(max-width:520px){ .resume-filter-grid { grid-template-columns: 1fr !important; } }
</style>
@endsection
