@extends('admin.layout', ['title' => 'مدیریت کاربران'])

@section('content')
<div>
  
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;flex-wrap:wrap;gap:12px">
    <div>
      <h1 style="margin:0 0 6px;font-size:22px;color:#0f172a">👤 اعضا و کاربران سامانه ۲ کیلو کاغذ</h1>
      <p style="margin:0;color:#64748b;font-size:13.5px">مشاهده شماره تماس کاربران، تعداد شرکت‌های ثبت‌شده و تعیین مدیران سیستم</p>
    </div>
  </div>

  <!-- نوار جستجو -->
  <form method="get" action="{{ route('admin.users') }}" style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px;margin-bottom:18px">
    <div style="display:grid;grid-template-columns:3fr auto;gap:10px;align-items:center">
      <input name="q" value="{{ request('q') }}" placeholder="جستجو بر اساس شماره موبایل یا نام کاربر..." style="border:1px solid #cbd5e1">
      <button type="submit" class="btn" style="background:var(--admin-primary);padding:9px 24px">جستجو</button>
    </div>
  </form>

  <!-- جدول کاربران -->
  <div class="admin-table-card">
    <table class="admin-table">
      <thead>
        <tr>
          <th>شماره تماس (نام کاربری)</th>
          <th>نام و نام خانوادگی</th>
          <th style="text-align:center">شرکت‌های ثبت‌شده</th>
          <th style="text-align:center">آگهی‌های ثبت‌شده</th>
          <th>تاریخ عضویت</th>
          <th style="text-align:center">سطح دسترسی</th>
          <th style="text-align:left">تغییر سطح</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $u)
          <tr>
            <td>
              <b style="font-size:14px;color:#0f172a;direction:ltr;display:inline-block">{{ $u->phone }}</b>
              @if($u->id === auth()->id())
                <span class="badge" style="background:#e0f2fe;color:#0369a1;font-size:10.5px">حساب شما</span>
              @endif
            </td>

            <td style="color:#334155">
              {{ $u->name ?: '—' }}
            </td>

            <td style="text-align:center">
              @if($u->companies_count > 0)
                <span class="badge ok" style="font-size:11.5px">{{ $u->companies_count }} شرکت</span>
              @else
                <span style="color:#94a3b8">۰</span>
              @endif
            </td>

            <td style="text-align:center">
              @if($u->job_postings_count > 0)
                <span class="badge" style="background:#f1f5f9;color:#334155;font-size:11.5px">{{ $u->job_postings_count }} آگهی</span>
              @else
                <span style="color:#94a3b8">۰</span>
              @endif
            </td>

            <td style="font-size:12.5px;color:#64748b">
              {{ $u->created_at->format('Y/m/d') }}
              <span style="display:block;font-size:11px;color:#94a3b8">{{ $u->created_at->diffForHumans() }}</span>
            </td>

            <td style="text-align:center">
              @if($u->isAdmin())
                <span class="badge gold" style="font-size:11.5px;font-weight:700">★ مدیر ارشد</span>
              @else
                <span style="font-size:12px;color:#64748b">کاربر عادی</span>
              @endif
            </td>

            <td style="text-align:left">
              @if($u->id !== auth()->id())
                <form method="post" action="{{ route('admin.users.toggle-admin', $u) }}" style="margin:0" onsubmit="return confirm('آیا از تغییر سطح دسترسی این کاربر اطمینان دارید؟')">
                  @csrf
                  <button type="submit" class="btn-action {{ $u->is_admin ? 'btn-reject' : 'btn-approve' }}">
                    {{ $u->is_admin ? 'لغو ادمین' : 'ارتقا به ادمین' }}
                  </button>
                </form>
              @else
                <span style="font-size:11px;color:#94a3b8">—</span>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" style="text-align:center;padding:40px;color:#64748b">
              کاربری یافت نشد.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <div style="padding:16px 20px;border-top:1px solid #e2e8f0">
      {{ $users->links() }}
    </div>
  </div>

</div>
@endsection
