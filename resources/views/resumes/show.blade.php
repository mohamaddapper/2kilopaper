@extends('layouts.app', ['title' => $resume->job_title . ' - ' . $resume->full_name])

@section('content')
<div class="container" style="max-width:920px;margin:28px auto 56px">
  <div style="margin-bottom:14px">
    <a href="{{ route('resumes.index') }}" style="font-size:13px;color:var(--primary);text-decoration:none;font-weight:700">→ بازگشت به بانک کارجویان</a>
  </div>

  <div class="card" style="padding:26px;margin-bottom:20px">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;margin-bottom:16px">
      <div>
        <h1 style="margin:0 0 6px;font-size:22px;color:var(--text)">{{ $resume->job_title }}</h1>
        <div style="font-size:15px;font-weight:700;color:var(--primary)">{{ $resume->full_name }}</div>
      </div>
      <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
        <span class="badge ok" style="font-size:12.5px;padding:4px 12px">
          {{ $resume->availability === 'available' ? 'آماده به کار' : 'مشغول به کار' }}
        </span>
        @if($resume->specialty)
          <span class="badge" style="background:#eff6ff;color:#0369a1;font-size:12px;padding:4px 12px">{{ $resume->specialty }}</span>
        @endif
      </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(175px,1fr));gap:12px;padding:14px;border-radius:10px;background:#f8fafc;border:1px solid var(--border);margin-bottom:20px;font-size:13px">
      <div>
        <span style="color:var(--muted);display:block;font-size:11.5px">موقعیت مکانی</span>
        <b style="color:var(--text)">{{ $resume->province->name ?? 'ایران' }}{{ $resume->city ? ' · ' . $resume->city->name : '' }}</b>
      </div>
      <div>
        <span style="color:var(--muted);display:block;font-size:11.5px">سابقه کار مرتبط</span>
        <b style="color:var(--text)">{{ $resume->experience_years > 0 ? $resume->experience_years . ' سال' : 'تازه‌کار / زیر ۱ سال' }}</b>
      </div>
      <div>
        <span style="color:var(--muted);display:block;font-size:11.5px">نوع همکاری</span>
        <b style="color:var(--text)">{{ $resume->workTypeLabel() }}</b>
      </div>
      <div>
        <span style="color:var(--muted);display:block;font-size:11.5px">حقوق درخواستی</span>
        <b style="color:var(--text)">{{ $resume->expected_salary ?: 'توافقی' }}</b>
      </div>
      <div>
        <span style="color:var(--muted);display:block;font-size:11.5px">اسکان / خوابگاه</span>
        <b style="color:var(--text)">{{ $resume->needs_accommodation ? 'نیاز به خوابگاه / اسکان دارد' : 'نیاز ندارد' }}</b>
      </div>
    </div>

    @if(!empty($resume->machines))
      <div style="margin-bottom:20px">
        <h3 style="margin:0 0 10px;font-size:15px;color:var(--text)">تخصص و تسلط بر دستگاه‌ها و مهارت‌ها</h3>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
          @foreach((array)$resume->machines as $m)
            <span class="badge" style="background:#eff6ff;color:#0369a1;font-size:12.5px;padding:5px 12px">{{ $m }}</span>
          @endforeach
        </div>
      </div>
    @endif

    @if($resume->bio)
      <div style="margin-bottom:22px">
        <h3 style="margin:0 0 8px;font-size:15px;color:var(--text)">شرح سوابق کاری و توانمندی‌ها</h3>
        <p style="margin:0;font-size:14px;line-height:1.9;color:var(--text);white-space:pre-line">{{ $resume->bio }}</p>
      </div>
    @endif

    <!-- بخش اطلاعات تماس (محافظت‌شده برای مشترکین) -->
    <div style="padding:18px;border-radius:12px;border:1.5px solid {{ $canSeeContact ? '#10b981' : '#f59e0b' }};background:{{ $canSeeContact ? '#ecfdf5' : '#fffbeb' }}">
      @if($canSeeContact)
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">
          <div>
            <div style="font-size:12.5px;color:#047857;font-weight:700;margin-bottom:4px">اطلاعات تماس کارجو</div>
            <a href="tel:{{ $resume->phone }}" style="font-size:18px;font-weight:900;color:#065f46;direction:ltr;display:inline-block;text-decoration:none">
              {!! svg_icon('phone', 16) !!} {{ $resume->phone }}
            </a>
          </div>
          @if($resume->resume_file)
            <a href="{{ asset($resume->resume_file) }}" target="_blank" class="btn small" style="background:#059669;color:#fff">
              {!! svg_icon('file-text', 15) !!} دانلود فایل رزومه (PDF)
            </a>
          @endif
        </div>
      @else
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">
          <div>
            <b style="font-size:14.5px;color:#92400e;display:block;margin-bottom:4px">شماره تماس و فایل رزومه کارجو برای کارفرمایان دارای اشتراک فعال نمایش داده می‌شود</b>
            <span style="font-size:12.5px;color:#b45309">
              شماره تماس: <span style="direction:ltr;display:inline-block">{{ substr($resume->phone, 0, 4) . '****' . substr($resume->phone, -3) }}</span>
            </span>
          </div>
          <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="btn small" style="background:#d97706;color:#fff;font-weight:700">
            {{ auth()->check() ? 'تهیه اشتراک و مشاهده شماره' : 'ورود کارفرما / تهیه اشتراک' }}
          </a>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
