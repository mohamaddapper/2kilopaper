@extends('admin.layout', ['title' => 'مدیریت کاربران'])

@section('content')
<div>
  
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;flex-wrap:wrap;gap:12px">
    <div>
      <h1 style="margin:0 0 6px;font-size:22px;color:#0f172a">{!! svg_icon('user', 15) !!} اعضا و کاربران سامانه ۲ کیلو کاغذ</h1>
      <p style="margin:0;color:#64748b;font-size:13.5px">مشاهده شماره تماس کاربران، تعداد شرکت‌های ثبت‌شده و تعیین مدیران سیستم</p>
    </div>
  </div>

  <!-- نوار جستجو -->
  <form method="get" action="{{ route('admin.users') }}" style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px;margin-bottom:18px">
    <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
      <input name="q" value="{{ request('q') }}" placeholder="جستجو بر اساس شماره موبایل یا نام کاربر..." style="flex:1;min-width:200px;border:1px solid #cbd5e1">
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
          <th style="text-align:left">عملیات</th>
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
                <span class="badge gold" style="font-size:11.5px;font-weight:700">{!! svg_icon('star-filled', 15) !!} مدیر ارشد</span>
              @else
                <span style="font-size:12px;color:#64748b">کاربر عادی</span>
              @endif
            </td>

            <td style="text-align:left">
              <div class="gear-dropdown">
                <button type="button" class="gear-btn" onclick="toggleGearMenu(this, event)" title="عملیات کاربر">
                  {!! svg_icon('settings', 16) !!}
                </button>
                <div class="gear-dropdown-menu">
                  <button type="button" class="gear-dropdown-item" onclick='closeAllGearMenus(); openUserEditModal(@json($u->id), @json($u->name ?? ""), @json($u->phone ?? ""), @json($u->email ?? ""), {{ $u->is_admin ? "true" : "false" }}, {{ $u->id === auth()->id() ? "true" : "false" }})'>
                    {!! svg_icon('edit', 14) !!} ویرایش کاربر
                  </button>

                  @if($u->id !== auth()->id())
                    <form method="post" action="{{ route('admin.users.toggle-admin', $u) }}" style="margin:0" onsubmit="return confirm('آیا از تغییر سطح دسترسی این کاربر اطمینان دارید؟')">
                      @csrf
                      <button type="submit" class="gear-dropdown-item" style="color:{{ $u->is_admin ? '#d97706' : '#16a34a' }}">
                        {!! svg_icon('star-filled', 14) !!} {{ $u->is_admin ? 'لغو ادمین' : 'ارتقا به ادمین' }}
                      </button>
                    </form>

                    <form method="post" action="{{ route('admin.users.destroy', $u) }}" style="margin:0" onsubmit="return confirm('آیا از حذف کامل کاربر «{{ addslashes($u->phone) }}» اطمینان دارید؟')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="gear-dropdown-item danger">
                        {!! svg_icon('trash', 14) !!} حذف کاربر
                      </button>
                    </form>
                  @endif
                </div>
              </div>
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

<!-- مودال ویرایش کاربر -->
<div id="userEditModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.55);z-index:999;align-items:center;justify-content:center">
  <div style="background:#fff;border-radius:12px;padding:24px;max-width:440px;width:92%;box-shadow:0 12px 32px rgba(0,0,0,0.22)">
    <h3 style="margin:0 0 14px;font-size:16px;color:#0f172a">ویرایش اطلاعات کاربر</h3>
    <form id="userEditForm" method="post" action="">
      @csrf
      @method('PUT')
      <div style="margin-bottom:12px">
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px">نام و نام خانوادگی</label>
        <input type="text" name="name" id="editUserName" style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;box-sizing:border-box">
      </div>
      <div style="margin-bottom:12px">
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px">شماره تماس *</label>
        <input type="text" name="phone" id="editUserPhone" required style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;direction:ltr;text-align:right;box-sizing:border-box">
      </div>
      <div style="margin-bottom:12px">
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px">ایمیل</label>
        <input type="email" name="email" id="editUserEmail" style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;direction:ltr;text-align:right;box-sizing:border-box">
      </div>
      <div style="margin-bottom:12px">
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px">رمز عبور جدید <span style="font-size:11.5px;color:#64748b;font-weight:400">(در صورت عدم تغییر خالی بگذارید)</span></label>
        <input type="password" name="password" placeholder="حداقل ۶ کاراکتر" style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;box-sizing:border-box">
      </div>
      <div id="editUserAdminWrap" style="margin-bottom:18px">
        <label style="display:inline-flex;align-items:center;gap:8px;font-size:13px;font-weight:600;cursor:pointer">
          <input type="hidden" name="is_admin" value="0">
          <input type="checkbox" name="is_admin" value="1" id="editUserIsAdmin">
          <span>دسترسی مدیر ارشد (ادمین)</span>
        </label>
      </div>
      <div style="display:flex;justify-content:flex-end;gap:8px">
        <button type="button" class="btn ghost small" onclick="closeUserEditModal()">انصراف</button>
        <button type="submit" class="btn small" style="background:#0284c7;color:#fff;font-weight:700">ذخیره تغییرات</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
function openUserEditModal(id, name, phone, email, isAdmin, isSelf) {
  document.getElementById('userEditForm').action = '{{ url("/admin/users") }}/' + id;
  document.getElementById('editUserName').value = name || '';
  document.getElementById('editUserPhone').value = phone || '';
  document.getElementById('editUserEmail').value = email || '';
  document.getElementById('editUserIsAdmin').checked = !!isAdmin;
  document.getElementById('editUserAdminWrap').style.display = isSelf ? 'none' : 'block';
  document.getElementById('userEditModal').style.display = 'flex';
}
function closeUserEditModal() {
  document.getElementById('userEditModal').style.display = 'none';
}
</script>
@endpush
@endsection
