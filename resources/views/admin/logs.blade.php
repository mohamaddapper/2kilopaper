@extends('admin.layout', ['title' => 'گزارش فعالیت همکاران'])

@section('content')
<div>
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px">
    <div>
      <h1 style="margin:0 0 6px;font-size:22px;color:#0f172a">{!! svg_icon('clock', 18) !!} گزارش فعالیت همکاران (Audit Log)</h1>
      <p style="margin:0;color:#64748b;font-size:13.5px">ثبت دقیق تمام تغییرات، تأییدها، ردها و ویرایش‌های تیم مدیریت</p>
    </div>
    <form method="get" action="{{ route('admin.logs') }}" style="display:flex;gap:8px;flex-wrap:wrap">
      <select name="user_id" style="padding:6px 10px;border:1px solid #cbd5e1;border-radius:8px">
        <option value="">همه همکاران</option>
        @foreach($admins as $adm)
          <option value="{{ $adm->id }}" @selected((int) request('user_id') === (int) $adm->id)>
            {{ $adm->name ?: $adm->phone }} ({{ $adm->phone }})
          </option>
        @endforeach
      </select>
      <select name="action" style="padding:6px 10px;border:1px solid #cbd5e1;border-radius:8px">
        <option value="">همه عملیات‌ها</option>
        <option value="company." @selected(request('action') === 'company.')>عملیات شرکت‌ها</option>
        <option value="category." @selected(request('action') === 'category.')>دسته‌بندی و سئو</option>
        <option value="job." @selected(request('action') === 'job.')>آگهی‌های شغلی</option>
        <option value="user." @selected(request('action') === 'user.')>کاربران و دسترسی‌ها</option>
      </select>
      <button type="submit" class="btn small">فیلتر</button>
    </form>
  </div>

  <div class="admin-table-card">
    <table class="admin-table">
      <thead>
        <tr>
          <th>همکار</th>
          <th>عملیات</th>
          <th>شرح رویداد</th>
          <th>آی‌پی</th>
          <th>زمان</th>
        </tr>
      </thead>
      <tbody>
        @forelse($logs as $log)
          <tr>
            <td>
              <b style="color:#0f172a">{{ $log->user->name ?? 'سیستم' }}</b>
              @if($log->user?->phone)
                <span style="display:block;font-size:11.5px;color:#64748b;direction:ltr;text-align:right">{{ $log->user->phone }}</span>
              @endif
            </td>
            <td>
              <span class="badge" style="background:#eff6ff;color:#0369a1;font-size:11.5px;direction:ltr">{{ $log->action }}</span>
            </td>
            <td style="color:#334155">{{ $log->description }}</td>
            <td style="font-size:12px;color:#64748b;direction:ltr;text-align:right">{{ $log->ip_address ?: '—' }}</td>
            <td style="font-size:12px;color:#64748b;white-space:nowrap">
              {{ $log->created_at?->diffForHumans() ?? '—' }}
              <span style="display:block;font-size:11px;color:#94a3b8">{{ $log->created_at?->format('Y/m/d H:i') }}</span>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" style="text-align:center;padding:36px;color:#64748b">هنوز گزارشی ثبت نشده است.</td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <div style="padding:16px 20px;border-top:1px solid #e2e8f0">
      {{ $logs->links() }}
    </div>
  </div>
</div>
@endsection
