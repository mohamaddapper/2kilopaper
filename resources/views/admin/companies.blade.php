@extends('admin.layout', ['title' => 'مدیریت شرکت‌ها'])

@section('content')
<div>
  
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;flex-wrap:wrap;gap:12px">
    <div>
      <h1 style="margin:0 0 6px;font-size:22px;color:#0f172a">{!! svg_icon('building', 15) !!} دایرکتوری و مدیریت شرکت‌های صنعتی</h1>
      <p style="margin:0;color:#64748b;font-size:13.5px">تأیید صلاحیت، مدیریت اشتراک‌های طلایی/نقره‌ای و نظارت بر کارخانجات</p>
    </div>
    <div style="display:flex;gap:8px">
      <a href="{{ route('companies.create') }}" target="_blank" class="btn small" style="background:var(--admin-primary);color:#fff;font-weight:700">
        + ثبت شرکت جدید
      </a>
    </div>
  </div>

  <!-- نوار فیلتر و جستجو -->
  <form method="get" action="{{ route('admin.companies') }}" style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px;margin-bottom:18px">
    <div class="admin-filter-grid">
      
      <input name="q" value="{{ request('q') }}" placeholder="جستجو بر اساس نام شرکت، تلفن، شعار..." style="border:1px solid #cbd5e1">

      <select name="status" style="border:1px solid #cbd5e1">
        <option value="all">همه وضعیت‌ها</option>
        <option value="pending" @selected(request('status') === 'pending')> در انتظار بررسی</option>
        <option value="approved" @selected(request('status') === 'approved')> تأیید و منتشرشده</option>
        <option value="rejected" @selected(request('status') === 'rejected')> رد/معلق‌شده</option>
      </select>

      <select name="tier" style="border:1px solid #cbd5e1">
        <option value="all">همه پلن‌ها</option>
        <option value="gold" @selected(request('tier') === 'gold')> اشتراک طلایی</option>
        <option value="silver" @selected(request('tier') === 'silver')> اشتراک نقره‌ای</option>
        <option value="bronze" @selected(request('tier') === 'bronze')> اشتراک برنزی</option>
        <option value="normal" @selected(request('tier') === 'normal')>پلن عادی (رایگان)</option>
      </select>

      <select name="cat" style="border:1px solid #cbd5e1">
        <option value="">همه زمینه‌ها</option>
        @foreach($categories as $c)
          <option value="{{ $c->id }}" @selected(request('cat') == $c->id)>{{ $c->name }}</option>
          @foreach($c->children as $ch)
            <option value="{{ $ch->id }}" @selected(request('cat') == $ch->id)>— {{ $ch->name }}</option>
          @endforeach
        @endforeach
      </select>

      <button type="submit" class="btn" style="background:var(--admin-primary);padding:9px 20px">اعمال فیلتر</button>
    </div>

    @if(request()->hasAny(['q', 'status', 'tier', 'cat']))
      <div style="margin-top:10px;display:flex;align-items:center;gap:8px">
        <span style="font-size:12px;color:#64748b">فیلترهای فعال:</span>
        <a href="{{ route('admin.companies') }}" style="color:#ef4444;font-size:12px;text-decoration:none;font-weight:600">{!! svg_icon('x', 15) !!} پاک‌سازی همه فیلترها</a>
      </div>
    @endif
  </form>

  <!-- جدول شرکت‌ها -->
  <div class="admin-table-card">
    <table class="admin-table">
      <thead>
        <tr>
          <th>لوگو و نام کارخانه / شرکت</th>
          <th>حوزه تخصصی و شهر</th>
          <th>کاربر ثبت‌کننده</th>
          <th>سطح اشتراک</th>
          <th style="text-align:center">وضعیت انتشار</th>
          <th style="text-align:center">بازدید</th>
          <th style="text-align:left">عملیات مدیریت</th>
        </tr>
      </thead>
      <tbody>
        @forelse($companies as $c)
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:12px">
                <div style="width:40px;height:40px;border-radius:8px;background:#f8fafc;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0">
                  @if($c->logo)
                    <img src="{{ asset($c->logo) }}" alt="{{ $c->name }}" style="width:100%;height:100%;object-fit:cover">
                  @else
                    <span style="font-weight:700;color:var(--admin-primary)">{{ mb_substr($c->name, 0, 1) }}</span>
                  @endif
                </div>
                <div>
                  <a href="{{ $c->url }}" target="_blank" style="font-size:14px;font-weight:700;color:#0f172a;text-decoration:none">{{ $c->name }}</a>
                  @if($c->slogan)
                    <span style="display:block;font-size:11.5px;color:#64748b">{{ $c->slogan }}</span>
                  @endif
                </div>
              </div>
            </td>

            <td>
              <span class="badge" style="background:#eff6ff;color:#0284c7;font-size:11.5px">
                {{ $c->category->name ?? '—' }}
              </span>
              <span style="display:block;font-size:12px;color:#64748b;margin-top:3px">
                {!! svg_icon('map-pin', 15) !!} {{ $c->city->name ?? 'نامشخص' }}
              </span>
            </td>

            <td style="font-size:12.5px;color:#334155">
              {{ $c->user->phone ?? '—' }}
              <span style="display:block;font-size:11px;color:#94a3b8">ثبت: {{ $c->created_at->format('Y/m/d') }}</span>
            </td>

            <td>
              @if($c->tier === 'gold')
                <span class="badge gold" style="font-size:11.5px">{!! svg_icon('star-filled', 15) !!} ویژه طلایی</span>
              @elseif($c->tier === 'silver')
                <span class="badge silver" style="font-size:11.5px">{!! svg_icon('star-filled', 15) !!} ویژه نقره‌ای</span>
              @elseif($c->tier === 'bronze')
                <span class="badge bronze" style="font-size:11.5px">{!! svg_icon('star-filled', 15) !!} ویژه برنزی</span>
              @else
                <span style="font-size:12px;color:#94a3b8">عادی</span>
              @endif

              @if($c->tier_expires_at)
                <span style="display:block;font-size:10.5px;color:#64748b;margin-top:2px">
                  تا {{ $c->tier_expires_at->format('Y/m/d') }}
                </span>
              @endif
            </td>

            <td style="text-align:center">
              @if($c->status === 'approved')
                <span class="badge ok" style="font-size:12px">{!! svg_icon('check', 15) !!} منتشرشده</span>
              @elseif($c->status === 'pending')
                <span class="badge" style="background:#fef3c7;color:#b45309;font-size:12px">{!! svg_icon('clock', 15) !!} در انتظار تأیید</span>
              @else
                <span class="badge bad" style="font-size:12px">{!! svg_icon('x', 15) !!} معلق / رد</span>
              @endif
            </td>

            <td style="text-align:center;font-size:13px;color:#475569">
              {!! svg_icon('eye', 15) !!} {{ number_format($c->views_count ?? 0) }}
            </td>

            <td style="text-align:left">
              <div class="gear-dropdown">
                <button type="button" class="gear-btn" onclick="toggleGearMenu(this, event)" title="عملیات مدیریت">
                  {!! svg_icon('settings', 16) !!}
                </button>
                <div class="gear-dropdown-menu">
                  @if($c->status !== 'approved')
                    <form method="post" action="{{ route('admin.companies.approve', $c) }}" style="margin:0">
                      @csrf
                      <button type="submit" class="gear-dropdown-item" style="color:#16a34a">
                        {!! svg_icon('check', 14) !!} تأیید و انتشار
                      </button>
                    </form>
                  @endif

                  @if($c->status !== 'rejected')
                    <form method="post" action="{{ route('admin.companies.reject', $c) }}" style="margin:0">
                      @csrf
                      <button type="submit" class="gear-dropdown-item" style="color:#d97706">
                        {!! svg_icon('x', 14) !!} رد / تعلیق
                      </button>
                    </form>
                  @endif

                  <a href="{{ route('companies.edit', $c) }}" target="_blank" class="gear-dropdown-item">
                    {!! svg_icon('edit', 14) !!} ویرایش کامل
                  </a>

                  <button type="button" class="gear-dropdown-item" onclick="closeAllGearMenus(); openTierModal({{ $c->id }}, '{{ addslashes($c->name) }}', '{{ $c->tier }}')">
                    {!! svg_icon('star-filled', 14) !!} تغییر اشتراک
                  </button>

                  <form method="post" action="{{ route('admin.companies.destroy', $c) }}" style="margin:0" onsubmit="return confirm('آیا از حذف کامل شرکت «{{ addslashes($c->name) }}» اطمینان دارید؟ این عمل غیرقابل بازگشت است.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="gear-dropdown-item danger">
                      {!! svg_icon('trash', 14) !!} حذف شرکت
                    </button>
                  </form>
                </div>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" style="text-align:center;padding:40px;color:#64748b">
              شرکتی با شرایط فیلتر انتخاب‌شده یافت نشد.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <div style="padding:16px 20px;border-top:1px solid #e2e8f0">
      {{ $companies->links() }}
    </div>
  </div>

</div>

<!-- مودال تغییر سطح اشتراک -->
<div id="tierModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:999;align-items:center;justify-content:center">
  <div style="background:#fff;border-radius:12px;padding:24px;max-width:420px;width:90%;box-shadow:0 10px 25px rgba(0,0,0,0.2)">
    <h3 style="margin:0 0 8px;font-size:16px;color:#0f172a" id="modalCompanyName">تعیین سطح اشتراک شرکت</h3>
    <p style="margin:0 0 16px;font-size:12.5px;color:#64748b">سطح اشتراک و مدت زمان اعتبار را انتخاب کنید:</p>

    <form id="tierForm" method="post" action="">
      @csrf
      <div style="margin-bottom:12px">
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px">سطح اشتراک:</label>
        <select name="tier" id="selectTier" style="width:100%;border:1px solid #cbd5e1">
          <option value="">عادی (رایگان)</option>
          <option value="gold"> اشتراک طلایی (بالاترین اولویت)</option>
          <option value="silver"> اشتراک نقره‌ای</option>
          <option value="bronze"> اشتراک برنزی</option>
        </select>
      </div>

      <div style="margin-bottom:18px">
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px">مدت زمان اعتبار:</label>
        <select name="months" style="width:100%;border:1px solid #cbd5e1">
          <option value="1">۱ ماهه</option>
          <option value="3">۳ ماهه</option>
          <option value="6">۶ ماهه</option>
          <option value="12" selected>۱۲ ماهه (۱ سال)</option>
          <option value="24">۲۴ ماهه (۲ سال)</option>
        </select>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:8px">
        <button type="button" class="btn ghost small" onclick="closeTierModal()">انصراف</button>
        <button type="submit" class="btn small" style="background:#f59e0b;color:#000;font-weight:700">ذخیره سطح اشتراک</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<style>
.gear-dropdown { position: relative; display: inline-block; }
.gear-btn {
  display: inline-flex; align-items: center; justify-content: center;
  width: 34px; height: 34px; border-radius: 8px;
  border: 1px solid #cbd5e1; background: #f8fafc; color: #475569;
  cursor: pointer; transition: all 0.15s ease;
}
.gear-btn:hover { border-color: #0284c7; color: #0284c7; background: #eff6ff; }
.gear-dropdown-menu {
  display: none; position: fixed; z-index: 9999; min-width: 170px;
  background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px;
  box-shadow: 0 10px 28px rgba(15, 23, 42, 0.14); padding: 6px; text-align: right;
}
.gear-dropdown-menu.open { display: block; }
.gear-dropdown-item {
  display: flex; align-items: center; gap: 8px; width: 100%; padding: 8px 10px;
  border: none; background: transparent; color: #334155; font-size: 13px;
  font-weight: 600; font-family: inherit; text-decoration: none;
  border-radius: 7px; cursor: pointer; text-align: right; box-sizing: border-box;
}
.gear-dropdown-item:hover { background: #f1f5f9; color: #0f172a; }
.gear-dropdown-item.danger { color: #dc2626; }
.gear-dropdown-item.danger:hover { background: #fef2f2; color: #b91c1c; }
</style>
<script>
function closeAllGearMenus() {
  document.querySelectorAll('.gear-dropdown-menu.open').forEach(m => m.classList.remove('open'));
}
function toggleGearMenu(btn, e) {
  if (e) e.stopPropagation();
  const menu = btn.nextElementSibling;
  const wasOpen = menu.classList.contains('open');
  closeAllGearMenus();
  if (!wasOpen) {
    menu.classList.add('open');
    const rect = btn.getBoundingClientRect();
    const menuRect = menu.getBoundingClientRect();
    let top = rect.bottom + 4;
    if (top + menuRect.height > window.innerHeight - 10) {
      top = Math.max(10, rect.top - menuRect.height - 4);
    }
    let left = rect.left;
    if (left + menuRect.width > window.innerWidth - 10) {
      left = window.innerWidth - menuRect.width - 10;
    }
    menu.style.top = top + 'px';
    menu.style.left = Math.max(10, left) + 'px';
  }
}
document.addEventListener('click', closeAllGearMenus);

function openTierModal(companyId, companyName, currentTier) {
 const modal = document.getElementById('tierModal');
 const title = document.getElementById('modalCompanyName');
 const form = document.getElementById('tierForm');
 const select = document.getElementById('selectTier');

 title.textContent = 'تعیین سطح اشتراک: ' + companyName;
 form.action = '{{ url("/admin/companies") }}/' + companyId + '/tier';
 select.value = currentTier || '';
 
 modal.style.display = 'flex';
}

function closeTierModal() {
 document.getElementById('tierModal').style.display = 'none';
}
</script>
@endpush
@endsection
