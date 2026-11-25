@extends('admin.layout', ['title' => 'مدیریت رزومه‌های کارجویان'])

@section('content')
<div>
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px">
    <div>
      <h1 style="margin:0 0 6px;font-size:22px;color:#0f172a">{!! svg_icon('users', 18) !!} مدیریت رزومه‌ها و کارجویان</h1>
      <p style="margin:0;color:#64748b;font-size:13.5px">تأیید، رد یا حذف رزومه‌های ثبت‌شده در بانک کارجویان صنعت</p>
    </div>
    <form method="get" action="{{ route('admin.resumes') }}" style="display:flex;gap:8px;flex-wrap:wrap">
      <input name="q" value="{{ request('q') }}" placeholder="نام، عنوان شغلی یا موبایل..." style="width:220px">
      <select name="status">
        <option value="all">همه وضعیت‌ها</option>
        <option value="pending" @selected(request('status') === 'pending')>در انتظار تأیید</option>
        <option value="approved" @selected(request('status') === 'approved')>تأییدشده</option>
        <option value="rejected" @selected(request('status') === 'rejected')>ردشده</option>
      </select>
      <button type="submit" class="btn small">فیلتر</button>
    </form>
  </div>

  <div class="admin-table-card">
    <table class="admin-table">
      <thead>
        <tr>
          <th>عنوان شغلی و نام کارجو</th>
          <th>تخصص / موقعیت</th>
          <th>سابقه و حقوق</th>
          <th style="text-align:center">وضعیت کارجو</th>
          <th style="text-align:center">وضعیت انتشار</th>
          <th style="text-align:left">عملیات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($resumes as $r)
          <tr>
            <td>
              <a href="{{ route('resumes.show', $r) }}" target="_blank" style="font-weight:700;color:#0f172a;text-decoration:none">{{ $r->job_title }}</a>
              <span style="display:block;font-size:12px;color:#64748b">{{ $r->full_name }} · <span style="direction:ltr;display:inline-block">{{ $r->phone }}</span></span>
            </td>
            <td style="font-size:12.5px;color:#475569">
              {{ $r->specialty ?: '—' }}
              <span style="display:block;font-size:11.5px;color:#64748b">{{ $r->province->name ?? '' }}{{ $r->city ? ' · ' . $r->city->name : '' }}</span>
            </td>
            <td style="font-size:12.5px">
              <b>{{ $r->experience_years }} سال</b>
              <span style="display:block;font-size:11.5px;color:#16a34a">{{ $r->expected_salary ?: 'توافقی' }}</span>
            </td>
            <td style="text-align:center">
              <span class="badge {{ $r->availability === 'available' ? 'ok' : '' }}" style="font-size:11.5px">
                {{ $r->availability === 'available' ? 'آماده به کار' : 'مشغول به کار' }}
              </span>
            </td>
            <td style="text-align:center">
              @if($r->status === 'approved')
                <span class="badge ok">تأییدشده</span>
              @elseif($r->status === 'rejected')
                <span class="badge bad">ردشده</span>
              @else
                <span class="badge warn">در انتظار تأیید</span>
              @endif
            </td>
            <td style="text-align:left">
              <div class="gear-dropdown">
                <button type="button" class="gear-btn" onclick="toggleGearMenu(this, event)" title="عملیات">
                  {!! svg_icon('settings', 16) !!}
                </button>
                <div class="gear-dropdown-menu">
                  @if($r->status !== 'approved')
                    <form method="post" action="{{ route('admin.resumes.approve', $r) }}" style="margin:0">
                      @csrf
                      <button type="submit" class="gear-dropdown-item" style="color:#16a34a">{!! svg_icon('check', 14) !!} تأیید و انتشار</button>
                    </form>
                  @endif
                  @if($r->status !== 'rejected')
                    <form method="post" action="{{ route('admin.resumes.reject', $r) }}" style="margin:0">
                      @csrf
                      <button type="submit" class="gear-dropdown-item" style="color:#d97706">{!! svg_icon('x', 14) !!} رد کردن</button>
                    </form>
                  @endif
                  <a href="{{ route('resumes.edit', $r) }}" target="_blank" class="gear-dropdown-item">{!! svg_icon('edit', 14) !!} ویرایش</a>
                  <form method="post" action="{{ route('admin.resumes.destroy', $r) }}" style="margin:0" onsubmit="return confirm('آیا از حذف این رزومه اطمینان دارید؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="gear-dropdown-item danger">{!! svg_icon('trash', 14) !!} حذف</button>
                  </form>
                </div>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" style="text-align:center;padding:36px;color:#64748b">رزومه‌ای ثبت نشده است.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
    <div style="padding:16px 20px;border-top:1px solid #e2e8f0">
      {{ $resumes->links() }}
    </div>
  </div>
</div>
@endsection
