@extends('admin.layout', ['title' => 'رزومه‌ها و متقاضیان'])

@section('content')
<div>
  
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;flex-wrap:wrap;gap:12px">
    <div>
      <h1 style="margin:0 0 6px;font-size:22px;color:#0f172a">{!! svg_icon('users', 15) !!} بانک جامع متقاضیان و رزومه‌های کارجو</h1>
      <p style="margin:0;color:#64748b;font-size:13.5px">نظارت بر درخواست‌های کارجویان، رزومه‌های ارسالی به کارخانجات و ارتباطات استخدامی</p>
    </div>
  </div>

  <!-- نوار فیلتر و جستجو -->
  <form method="get" action="{{ route('admin.applications') }}" style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px;margin-bottom:18px">
    <div class="admin-filter-grid">
      
      <input name="q" value="{{ request('q') }}" placeholder="جستجو بر اساس نام متقاضی، شماره تماس، آخرین محل کار..." style="border:1px solid #cbd5e1">

      <select name="status" style="border:1px solid #cbd5e1">
        <option value="all">همه وضعیت‌ها</option>
        <option value="new" @selected(request('status') === 'new')>جدید (بررسی‌نشده)</option>
        <option value="reviewed" @selected(request('status') === 'reviewed')>در حال بررسی</option>
        <option value="interview" @selected(request('status') === 'interview')>دعوت به مصاحبه</option>
        <option value="hired" @selected(request('status') === 'hired')>استخدام شده</option>
        <option value="rejected" @selected(request('status') === 'rejected')>رد شده</option>
      </select>

      <button type="submit" class="btn" style="background:var(--admin-primary);padding:9px 20px">اعمال فیلتر</button>
    </div>
  </form>

  <!-- جدول متقاضیان -->
  <div class="admin-table-card">
    <table class="admin-table">
      <thead>
        <tr>
          <th>نام متقاضی کار</th>
          <th>برای کدام آگهی و شرکت</th>
          <th>شماره تماس مستقیم</th>
          <th>سابقه و تخصص</th>
          <th style="text-align:center">فایل رزومه</th>
          <th style="text-align:center">وضعیت</th>
          <th style="text-align:left">تاریخ ارسال</th>
        </tr>
      </thead>
      <tbody>
        @forelse($applications as $app)
          <tr>
            <td>
              <b style="font-size:14px;color:#0f172a">{{ $app->full_name }}</b>
              @if($app->birth_year)
                <span style="display:block;font-size:11.5px;color:#64748b">متولد {{ $app->birth_year }}</span>
              @endif
            </td>

            <td>
              @if($app->jobPosting)
                <a href="{{ route('jobs.show', $app->jobPosting) }}" target="_blank" style="color:var(--admin-primary);font-weight:600;text-decoration:none">
                  {{ $app->jobPosting->title }}
                </a>
                <span style="display:block;font-size:11.5px;color:#64748b">
                  کارخانه: {{ $app->jobPosting->company->name ?? 'مستقل' }}
                </span>
              @else
                <span style="color:#94a3b8">آگهی حذف شده</span>
              @endif
            </td>

            <td>
              <a href="tel:{{ $app->phone }}" style="direction:ltr;display:inline-block;color:#0284c7;font-weight:700">
                {{ $app->phone }}
              </a>
              @if($app->email)
                <span style="display:block;font-size:11px;color:#64748b">{{ $app->email }}</span>
              @endif
            </td>

            <td style="font-size:12.5px;color:#334155">
              <span>سابقه: {{ $app->experience_years ?: 'نامشخص' }}</span>
              @if($app->last_company)
                <span style="display:block;font-size:11.5px;color:#64748b">آخرین کارگاه: {{ $app->last_company }}</span>
              @endif
            </td>

            <td style="text-align:center">
              @if($app->resume_file)
                <a href="{{ asset($app->resume_file) }}" target="_blank" class="btn-action btn-view" style="font-size:11.5px">
                  {!! svg_icon('download', 15) !!} دانلود PDF
                </a>
              @else
                <span style="font-size:11.5px;color:#94a3b8">بدون فایل</span>
              @endif
            </td>

            <td style="text-align:center">
              <span class="badge {{ $app->status_badge_class }}" style="font-size:11.5px">
                {{ $app->status_label }}
              </span>
            </td>

            <td style="text-align:left;font-size:12px;color:#64748b">
              {{ $app->created_at->diffForHumans() }}
              <span style="display:block;font-size:11px;color:#94a3b8">{{ $app->created_at->format('Y/m/d') }}</span>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" style="text-align:center;padding:40px;color:#64748b">
              رزومه‌ای با شرایط فیلتر انتخاب‌شده یافت نشد.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <div style="padding:16px 20px;border-top:1px solid #e2e8f0">
      {{ $applications->links() }}
    </div>
  </div>

</div>
@endsection
