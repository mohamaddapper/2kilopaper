@extends('layouts.app', ['title' => 'متقاضیان آگهی ' . $job->title, 'dashboardNav' => true])

@section('content')
<div class="page-wrapper" style="margin:24px 0 48px">
  
  <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:20px">
    <div>
      <div style="font-size:13px;color:var(--muted);margin-bottom:4px">
        <a href="{{ route('dashboard') }}#jobs" style="color:var(--primary);text-decoration:none">مدیریت آگهی‌ها</a> / متقاضیان
      </div>
      <h1 style="margin:0 0 6px;font-size:20px;color:var(--text)">
        👥 متقاضیان و رزومه‌های دریافتی: {{ $job->title }}
      </h1>
      <span style="font-size:13px;color:var(--muted)">
        تعداد کل متقاضیان: <b>{{ $job->applications->count() }} نفر</b>
        @if($job->company)
          · کارخانه: {{ $job->company->name }}
        @endif
      </span>
    </div>
    
    <div style="display:flex;gap:8px">
      <a href="{{ route('jobs.show', $job) }}" target="_blank" class="btn ghost small">مشاهده آگهی در سایت ↗</a>
      <a href="{{ route('dashboard') }}#jobs" class="btn small">بازگشت به میز کار</a>
    </div>
  </div>

  @if($job->applications->count() > 0)
    <div style="display:flex;flex-direction:column;gap:14px">
      @foreach($job->applications as $app)
        <div class="card" style="padding:20px;background:#fff;border-radius:12px;border:1px solid var(--border)">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;margin-bottom:12px">
            
            <div>
              <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
                <b style="font-size:16px;color:var(--text)">{{ $app->full_name }}</b>
                <span class="badge {{ $app->status_badge_class }}" id="badge_status_{{ $app->id }}" style="font-size:12px">
                  {{ $app->status_label }}
                </span>
                <span style="font-size:12px;color:var(--muted)">📅 {{ $app->created_at->diffForHumans() }}</span>
                <span style="font-size:12px;color:var(--muted)">👁️ {{ number_format($app->views_count ?? 0) }} بار مشاهده</span>
              </div>

              <div style="display:flex;align-items:center;gap:14px;margin-top:8px;font-size:13.5px;color:var(--muted);flex-wrap:wrap">
                <span>📞 <b>شماره تماس:</b> <a href="tel:{{ $app->phone }}" style="color:var(--primary);font-weight:700">{{ $app->phone }}</a></span>
                @if($app->email)
                  <span>✉️ {{ $app->email }}</span>
                @endif
                @if($app->birth_year)
                  <span>🎂 متولد {{ $app->birth_year }}</span>
                @endif
                @if($app->military_status)
                  <span>🎖️ {{ $app->military_status }}</span>
                @endif
              </div>
            </div>

            <!-- تغییر وضعیت رزومه -->
            <div style="display:flex;align-items:center;gap:8px">
              <span style="font-size:12.5px;color:var(--muted)">تغییر وضعیت:</span>
              <select onchange="updateAppStatus({{ $app->id }}, this.value)" style="padding:6px 12px;font-size:12.5px;border-radius:6px">
                <option value="new" @selected($app->status == 'new')>جدید (بررسی‌نشده)</option>
                <option value="reviewed" @selected($app->status == 'reviewed')>در حال بررسی</option>
                <option value="interview" @selected($app->status == 'interview')>دعوت به مصاحبه</option>
                <option value="rejected" @selected($app->status == 'rejected')>رد متقاضی</option>
                <option value="hired" @selected($app->status == 'hired')>استخدام شد</option>
              </select>
            </div>

          </div>

          <!-- مشخصات حرفه‌ای -->
          <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:10px;background:#f8fafc;padding:12px 14px;border-radius:8px;margin-bottom:12px;font-size:13px">
            <div>
              <span style="color:var(--muted);display:block">میزان سابقه کار:</span>
              <b>{{ $app->experience_years ?: 'نامشخص' }}</b>
            </div>
            <div>
              <span style="color:var(--muted);display:block">آخرین محل کار / کارخانه:</span>
              <b>{{ $app->last_company ?: 'ذکر نشده' }}</b>
            </div>
            <div>
              <span style="color:var(--muted);display:block">حقوق درخواستی:</span>
              <b style="color:#16a34a">{{ $app->expected_salary ?: 'توافقی' }}</b>
            </div>
            <div>
              <span style="color:var(--muted);display:block">فایل رزومه:</span>
              @if($app->resume_file)
                <a href="{{ asset($app->resume_file) }}" target="_blank" class="btn small" style="padding:2px 10px;font-size:11.5px;background:#0284c7">
                  📥 دانلود رزومه PDF
                </a>
              @else
                <span style="color:var(--muted)">فایلی پیوست نشده</span>
              @endif
            </div>
          </div>

          @if($app->cover_letter)
            <div style="font-size:13.5px;color:#334155;background:#fffdfa;border:1px solid #fed7aa;border-radius:8px;padding:10px 14px;margin-bottom:10px">
              <b>پیام یا توضیحات کارجو:</b>
              <p style="margin:4px 0 0;line-height:1.7">{{ $app->cover_letter }}</p>
            </div>
          @endif

          <!-- یادداشت محرمانه کارفرما -->
          <div style="display:flex;gap:8px;align-items:center;margin-top:10px">
            <input type="text" id="note_input_{{ $app->id }}" value="{{ $app->employer_note }}" placeholder="یادداشت محرمانه کارفرما (مثال: پنجشنبه ساعت ۱۰ صبح مصاحبه حضوری)..." style="flex:1;padding:7px 12px;font-size:12.5px;border:1px solid var(--border);border-radius:6px">
            <button type="button" onclick="saveAppNote({{ $app->id }})" class="btn ghost small" style="padding:6px 14px;font-size:12px">ذخیره یادداشت</button>
          </div>
        </div>
      @endforeach
    </div>
  @else
    <div class="card" style="padding:48px;text-align:center;color:var(--muted);background:#fff;border-radius:12px">
      <span style="font-size:42px;display:block;margin-bottom:12px">📭</span>
      <h3 style="margin:0 0 8px;color:var(--text)">هنوز متقاضی برای این آگهی ثبت نشده است.</h3>
      <p style="margin:0;font-size:14px">به محض ارسال رزومه توسط کارجویان، اطلاعات آن‌ها در این صفحه نمایش داده خواهد شد.</p>
    </div>
  @endif

</div>

<script>
function updateAppStatus(appId, newStatus) {
  fetch('{{ url("/dashboard/applications") }}/' + appId + '/status', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}',
      'Accept': 'application/json'
    },
    body: JSON.stringify({ status: newStatus })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      const badge = document.getElementById('badge_status_' + appId);
      if (badge) {
        const labels = {
          'new': 'جدید',
          'reviewed': 'در حال بررسی',
          'interview': 'دعوت به مصاحبه',
          'rejected': 'رد متقاضی',
          'hired': 'استخدام شد'
        };
        const classes = {
          'new': 'badge silver',
          'reviewed': 'badge warn',
          'interview': 'badge ok',
          'rejected': 'badge bad',
          'hired': 'badge gold'
        };
        badge.textContent = labels[newStatus] || newStatus;
        badge.className = classes[newStatus] || 'badge';
      }
    }
  })
  .catch(err => console.error(err));
}

function saveAppNote(appId) {
  const noteInput = document.getElementById('note_input_' + appId);
  if (!noteInput) return;

  fetch('{{ url("/dashboard/applications") }}/' + appId + '/status', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}',
      'Accept': 'application/json'
    },
    body: JSON.stringify({ employer_note: noteInput.value })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert('یادداشت با موفقیت ذخیره شد.');
    }
  })
  .catch(err => console.error(err));
}
</script>
@endsection
