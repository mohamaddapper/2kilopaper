@extends('admin.layout', ['title' => 'مدیریت آگهی‌های شغلی'])

@section('content')
<div>
  
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;flex-wrap:wrap;gap:12px">
    <div>
      <h1 style="margin:0 0 6px;font-size:22px;color:#0f172a">💼 مدیریت فرصت‌های شغلی و استخدام</h1>
      <p style="margin:0;color:#64748b;font-size:13.5px">نظارت بر آگهی‌های کارخانجات، اعطای نشان فوری و مدیریت وضعیت انتشار</p>
    </div>
    <div style="display:flex;gap:8px">
      <a href="{{ route('employer.jobs.create') }}" target="_blank" class="btn small" style="background:var(--admin-primary);color:#fff;font-weight:700">
        + درج آگهی جدید
      </a>
    </div>
  </div>

  <!-- نوار فیلتر و جستجو -->
  <form method="get" action="{{ route('admin.jobs') }}" style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px;margin-bottom:18px">
    <div style="display:grid;grid-template-columns:2fr 1fr auto auto;gap:10px;align-items:center">
      
      <input name="q" value="{{ request('q') }}" placeholder="جستجو بر اساس عنوان شغلی، نام شرکت یا توضیحات..." style="border:1px solid #cbd5e1">

      <select name="status" style="border:1px solid #cbd5e1">
        <option value="all">همه وضعیت‌ها</option>
        <option value="active" @selected(request('status') === 'active')>✓ آگهی‌های فعال</option>
        <option value="closed" @selected(request('status') === 'closed')>✕ متوقف / منقضی</option>
      </select>

      <label style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;white-space:nowrap;user-select:none;font-size:13px;font-weight:600">
        <input type="checkbox" name="immediate" value="1" @checked(request('immediate')) style="width:16px!important;height:16px!important;accent-color:#ef4444">
        <span>⚡ فقط آگهی‌های فوری</span>
      </label>

      <button type="submit" class="btn" style="background:var(--admin-primary);padding:9px 20px">اعمال فیلتر</button>
    </div>
  </form>

  <!-- جدول آگهی‌ها -->
  <div class="admin-table-card">
    <table class="admin-table">
      <thead>
        <tr>
          <th>عنوان آگهی شغلی</th>
          <th>کارخانه / شرکت</th>
          <th>موقعیت و نوع کار</th>
          <th>حقوق پیشنهادی</th>
          <th style="text-align:center">نشان فوری</th>
          <th style="text-align:center">وضعیت</th>
          <th style="text-align:center">بازدید</th>
          <th style="text-align:left">عملیات ادمین</th>
        </tr>
      </thead>
      <tbody>
        @forelse($jobs as $j)
          <tr>
            <td>
              <a href="{{ route('jobs.show', $j) }}" target="_blank" style="color:#0f172a;font-weight:700;text-decoration:none;font-size:14px">
                {{ $j->title }}
              </a>
              <span style="display:block;font-size:11.5px;color:#64748b;margin-top:2px">
                ثبت: {{ $j->created_at->diffForHumans() }} · کاربر: {{ $j->user->phone ?? '—' }}
              </span>
            </td>

            <td>
              @if($j->company)
                <a href="{{ route('company', $j->company) }}" target="_blank" style="color:var(--admin-primary);text-decoration:none;font-weight:600">
                  {{ $j->company->name }}
                </a>
              @else
                <span style="color:#94a3b8">مستقل (بدون شرکت)</span>
              @endif
            </td>

            <td>
              <span>{{ $j->province->name ?? '' }} {{ $j->city ? '· ' . $j->city->name : '' }}</span>
              <span style="display:block;font-size:11.5px;color:#64748b">{{ $j->job_type_label }}</span>
            </td>

            <td style="font-size:12.5px;color:#16a34a">
              {{ $j->salary_text }}
            </td>

            <td style="text-align:center">
              <form method="post" action="{{ route('admin.jobs.immediate', $j) }}" style="margin:0">
                @csrf
                <button type="submit" class="btn-action" style="background:{{ $j->is_immediate ? '#fee2e2' : '#f1f5f9' }};color:{{ $j->is_immediate ? '#dc2626' : '#64748b' }}" title="تغییر وضعیت نشان فوری">
                  {{ $j->is_immediate ? '⚡ فوری' : 'عادی' }}
                </button>
              </form>
            </td>

            <td style="text-align:center">
              <form method="post" action="{{ route('admin.jobs.toggle', $j) }}" style="margin:0">
                @csrf
                <button type="submit" class="btn-action {{ $j->status === 'active' ? 'btn-approve' : 'btn-reject' }}">
                  {{ $j->status === 'active' ? '✓ فعال' : 'متوقف' }}
                </button>
              </form>
            </td>

            <td style="text-align:center;font-size:13px;color:#475569">
              👁️ {{ number_format($j->views_count ?? 0) }}
            </td>

            <td style="text-align:left">
              <div style="display:inline-flex;gap:6px;align-items:center">
                <a href="{{ route('jobs.show', $j) }}" target="_blank" class="btn-action btn-view">
                  نمایش ↗
                </a>
                <a href="{{ route('employer.jobs.edit', $j) }}" target="_blank" class="btn-action" style="background:#f1f5f9;color:#334155">
                  ✏️ ویرایش
                </a>
                <form method="post" action="{{ route('admin.jobs.destroy', $j) }}" style="margin:0" onsubmit="return confirm('آیا از حذف این آگهی اطمینان دارید؟')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-action btn-reject">حذف</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" style="text-align:center;padding:40px;color:#64748b">
              آگهی با شرایط انتخاب‌شده یافت نشد.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <div style="padding:16px 20px;border-top:1px solid #e2e8f0">
      {{ $jobs->links() }}
    </div>
  </div>

</div>
@endsection
