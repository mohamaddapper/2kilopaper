@extends('admin.layout', ['title' => 'مدیریت کاربران و همکاران'])

@section('content')
<div>

  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px">
    <div>
      <h1 style="margin:0 0 6px;font-size:22px;color:#0f172a">{!! svg_icon('user', 16) !!} مدیریت کاربران و دسترسی همکاران</h1>
      <p style="margin:0;color:#64748b;font-size:13.5px">تعیین نقش همکاران، مجوزهای بخش‌به‌بخش و مشاهده آخرین زمان ورود</p>
    </div>
    <form method="get" action="{{ route('admin.users') }}" style="display:flex;gap:8px">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="شماره تماس یا نام کاربر..." style="width:240px">
      <button type="submit" class="btn small">جستجو</button>
    </form>
  </div>

  <div class="admin-table-card">
    <table class="admin-table">
      <thead>
        <tr>
          <th>شماره تماس (موبایل)</th>
          <th>نام کاربر</th>
          <th style="text-align:center">شرکت‌ها</th>
          <th style="text-align:center">آگهی‌ها</th>
          <th>عضویت / آخرین ورود</th>
          <th style="text-align:center">نقش و دسترسی</th>
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
              @if($u->last_login_at)
                <span style="display:block;font-size:11px;color:#0284c7">آخرین ورود: {{ $u->last_login_at->diffForHumans() }}</span>
              @else
                <span style="display:block;font-size:11px;color:#94a3b8">{{ $u->created_at->diffForHumans() }}</span>
              @endif
            </td>

            <td style="text-align:center">
              @if($u->isSuperAdmin())
                <span class="badge gold" style="font-size:11.5px;font-weight:700">{!! svg_icon('star-filled', 14) !!} مدیر ارشد</span>
              @elseif($u->isAdmin())
                <span class="badge" style="background:#e0f2fe;color:#0369a1;font-size:11.5px;font-weight:700">{{ $u->roleLabel() }}</span>
              @else
                <span style="font-size:12px;color:#64748b">کاربر عادی</span>
              @endif
            </td>

            <td style="text-align:left">
              @if(!$u->isSuperAdmin() || auth()->user()->isSuperAdmin())
                <div class="gear-dropdown">
                  <button type="button" class="gear-btn" onclick="toggleGearMenu(this, event)" title="عملیات کاربر">
                    {!! svg_icon('settings', 16) !!}
                  </button>
                  <div class="gear-dropdown-menu">
                    <button type="button" class="gear-dropdown-item" onclick='closeAllGearMenus(); openUserEditModal(@json($u->id), @json($u->name ?? ""), @json($u->phone ?? ""), {{ $u->is_admin ? "true" : "false" }}, @json($u->admin_role ?? "moderator"), @json($u->admin_permissions ?? []), {{ $u->id === auth()->id() ? "true" : "false" }})'>
                      {!! svg_icon('edit', 14) !!} ویرایش و دسترسی‌ها
                    </button>

                    @if($u->id !== auth()->id())
                      <form method="post" action="{{ route('admin.users.toggle-admin', $u) }}" style="margin:0" onsubmit="return confirm('آیا از تغییر سطح دسترسی این کاربر اطمینان دارید؟')">
                        @csrf
                        <button type="submit" class="gear-dropdown-item" style="color:{{ $u->is_admin ? '#d97706' : '#16a34a' }}">
                          {!! svg_icon('star-filled', 14) !!} {{ $u->is_admin ? 'قطع دسترسی مدیریت' : 'افزودن به تیم مدیریت' }}
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
              @else
                <span style="font-size:11px;color:#94a3b8">محافظت‌شده</span>
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

<!-- مودال ویرایش کاربر و دسترسی همکار -->
<div id="userEditModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.55);z-index:999;align-items:center;justify-content:center;padding:14px">
  <div style="background:#fff;border-radius:12px;padding:24px;max-width:520px;width:100%;max-height:90vh;overflow-y:auto;box-shadow:0 12px 32px rgba(0,0,0,0.22)">
    <h3 style="margin:0 0 14px;font-size:16px;color:#0f172a">ویرایش کاربر و تنظیم دسترسی همکار</h3>
    <form id="userEditForm" method="post" action="">
      @csrf
      @method('PUT')
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px">
        <div>
          <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px">نام و نام خانوادگی</label>
          <input type="text" name="name" id="editUserName" style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;box-sizing:border-box">
        </div>
        <div>
          <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px">شماره تماس *</label>
          <input type="text" name="phone" id="editUserPhone" required style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;direction:ltr;text-align:right;box-sizing:border-box">
        </div>
      </div>

      <div id="editUserAdminSection" style="border-top:1px solid #e2e8f0;padding-top:14px;margin-bottom:16px">
        <label style="display:inline-flex;align-items:center;gap:8px;font-size:13.5px;font-weight:700;cursor:pointer;margin-bottom:12px">
          <input type="hidden" name="is_admin" value="0">
          <input type="checkbox" name="is_admin" value="1" id="editUserIsAdmin" onchange="toggleAdminRoleBox()">
          <span>عضو تیم مدیریت (دسترسی به پنل ادمین)</span>
        </label>

        <div id="editUserRoleBox" style="display:none;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:14px">
          <div style="margin-bottom:12px">
            <label style="display:block;font-size:12.5px;font-weight:700;margin-bottom:5px;color:#334155">نقش آماده همکار:</label>
            <select name="admin_role" id="editUserRole" onchange="applyRolePreset(this.value)" style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px">
              @foreach(\App\Models\User::ROLE_PRESETS as $rKey => $rInfo)
                @if($rKey !== 'super_admin' || auth()->user()->isSuperAdmin())
                  <option value="{{ $rKey }}">{{ $rInfo['label'] }}</option>
                @endif
              @endforeach
            </select>
          </div>

          <div>
            <label style="display:block;font-size:12.5px;font-weight:700;margin-bottom:8px;color:#334155">مجوزهای بخش‌به‌بخش:</label>
            <div style="display:grid;grid-template-columns:1fr;gap:7px">
              @foreach(\App\Models\User::ALL_PERMISSIONS as $pKey => $pLabel)
                <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;color:#1e293b">
                  <input type="checkbox" name="admin_permissions[]" value="{{ $pKey }}" class="perm-checkbox" onchange="document.getElementById('editUserRole').value='custom'">
                  <span>{{ $pLabel }}</span>
                </label>
              @endforeach
            </div>
          </div>
        </div>
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
const ROLE_PRESETS = @json(\App\Models\User::ROLE_PRESETS);

function toggleAdminRoleBox() {
  const checked = document.getElementById('editUserIsAdmin').checked;
  document.getElementById('editUserRoleBox').style.display = checked ? 'block' : 'none';
}

function applyRolePreset(roleKey) {
  const preset = ROLE_PRESETS[roleKey];
  if (!preset || roleKey === 'custom') return;
  const perms = preset.permissions || [];
  document.querySelectorAll('.perm-checkbox').forEach(cb => {
    cb.checked = perms.includes(cb.value);
  });
}

function openUserEditModal(id, name, phone, isAdmin, role, perms, isSelf) {
  document.getElementById('userEditForm').action = '{{ url("/admin/users") }}/' + id;
  document.getElementById('editUserName').value = name || '';
  document.getElementById('editUserPhone').value = phone || '';
  document.getElementById('editUserIsAdmin').checked = !!isAdmin;
  document.getElementById('editUserAdminSection').style.display = isSelf ? 'none' : 'block';

  const roleSelect = document.getElementById('editUserRole');
  roleSelect.value = role || 'moderator';

  const activePerms = (Array.isArray(perms) && perms.length > 0)
    ? perms
    : ((ROLE_PRESETS[role] && ROLE_PRESETS[role].permissions) || []);

  document.querySelectorAll('.perm-checkbox').forEach(cb => {
    cb.checked = activePerms.includes(cb.value);
  });

  toggleAdminRoleBox();
  document.getElementById('userEditModal').style.display = 'flex';
}
function closeUserEditModal() {
  document.getElementById('userEditModal').style.display = 'none';
}
</script>
@endpush
@endsection
