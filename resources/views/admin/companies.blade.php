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
        <option value="all">همه وضعیت‌های ویژه</option>
        <option value="gold" @selected(request('tier') === 'gold')>ویژه طلایی</option>
        <option value="silver" @selected(request('tier') === 'silver')>ویژه نقره‌ای</option>
        <option value="bronze" @selected(request('tier') === 'bronze')>ویژه برنزی</option>
        <option value="normal" @selected(request('tier') === 'normal')>بدون ویژه (عادی)</option>
      </select>

      <select name="subscription" style="border:1px solid #cbd5e1">
        <option value="all">همه اشتراک‌ها</option>
        <option value="any" @selected(request('subscription') === 'any')>دارای اشتراک فعال</option>
        <option value="12m" @selected(request('subscription') === '12m')>اشتراک ۱ ساله</option>
        <option value="6m" @selected(request('subscription') === '6m')>اشتراک ۶ ماهه</option>
        <option value="3m" @selected(request('subscription') === '3m')>اشتراک ۳ ماهه</option>
        <option value="none" @selected(request('subscription') === 'none')>بدون اشتراک</option>
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
                  @if($c->admin_note)
                    <span style="display:inline-block;margin-top:4px;padding:2px 8px;border-radius:6px;background:#fffbeb;border:1px solid #fde68a;color:#92400e;font-size:11px">
                      یادداشت: {{ \Illuminate\Support\Str::limit($c->admin_note, 60) }}
                    </span>
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
              @if($c->activeTier() === 'gold')
                <span class="badge gold" style="font-size:11.5px">{!! svg_icon('star-filled', 14) !!} ویژه طلایی</span>
              @elseif($c->activeTier() === 'silver')
                <span class="badge silver" style="font-size:11.5px">{!! svg_icon('star-filled', 14) !!} ویژه نقره‌ای</span>
              @elseif($c->activeTier() === 'bronze')
                <span class="badge bronze" style="font-size:11.5px">{!! svg_icon('star-filled', 14) !!} ویژه برنزی</span>
              @endif

              @if($c->activeSubscription())
                <span class="badge ok" style="font-size:11px;margin-top:2px;display:inline-block">{{ $c->subscriptionLabel() }}</span>
              @endif

              @if(!$c->activeTier() && !$c->activeSubscription())
                <span style="font-size:12px;color:#94a3b8">عادی (رایگان)</span>
              @endif

              @if($c->tier_expires_at || $c->subscription_expires_at)
                <span style="display:block;font-size:10.5px;color:#64748b;margin-top:2px">
                  @if($c->tier_expires_at) ویژه تا {{ $c->tier_expires_at->format('Y/m/d') }} @endif
                  @if($c->subscription_expires_at) {{ $c->tier_expires_at ? '·' : '' }} اشتراک تا {{ $c->subscription_expires_at->format('Y/m/d') }} @endif
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

                  <button type="button" class="gear-dropdown-item" onclick='closeAllGearMenus(); openNoteModal({{ $c->id }}, @json($c->name), @json($c->admin_note ?? ""))'>
                    {!! svg_icon('message', 14) !!} یادداشت داخلی
                  </button>

                  @if(auth()->user()->hasPermission('companies_tier'))
                    <button type="button" class="gear-dropdown-item" onclick="closeAllGearMenus(); openTierModal({{ $c->id }}, '{{ addslashes($c->name) }}', '{{ $c->tier }}', '{{ $c->subscription_plan }}')">
                      {!! svg_icon('star-filled', 14) !!} ویژه و اشتراک
                    </button>
                  @endif

                  @if(auth()->user()->hasPermission('companies_delete'))
                    <form method="post" action="{{ route('admin.companies.destroy', $c) }}" style="margin:0" onsubmit="return confirm('آیا از حذف کامل شرکت «{{ addslashes($c->name) }}» اطمینان دارید؟ این عمل غیرقابل بازگشت است.')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="gear-dropdown-item danger">
                        {!! svg_icon('trash', 14) !!} حذف شرکت
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

<!-- مودال تعیین ویژه و اشتراک شرکت -->
<div id="tierModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:999;align-items:center;justify-content:center">
  <div style="background:#fff;border-radius:12px;padding:24px;max-width:440px;width:92%;box-shadow:0 10px 25px rgba(0,0,0,0.2)">
    <h3 style="margin:0 0 8px;font-size:16px;color:#0f172a" id="modalCompanyName">تنظیم ویژه و اشتراک شرکت</h3>
    <p style="margin:0 0 16px;font-size:12.5px;color:#64748b">وضعیت ویژه تبلیغاتی و پلن اشتراک شرکت را انتخاب کنید (حداکثر مدت ۱ سال):</p>

    <form id="tierForm" method="post" action="">
      @csrf
      <div style="margin-bottom:12px">
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px">وضعیت ویژه تبلیغاتی:</label>
        <select name="tier" id="selectTier" style="width:100%;border:1px solid #cbd5e1;padding:8px;border-radius:8px">
          <option value="">بدون ویژه (عادی)</option>
          <option value="gold">ویژه طلایی (اولویت ۱)</option>
          <option value="silver">ویژه نقره‌ای (اولویت ۲)</option>
          <option value="bronze">ویژه برنزی (اولویت ۳)</option>
        </select>
      </div>

      <div style="margin-bottom:12px">
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px">مدت اعتبار ویژه:</label>
        <select name="months" style="width:100%;border:1px solid #cbd5e1;padding:8px;border-radius:8px">
          <option value="1">۱ ماهه</option>
          <option value="3">۳ ماهه</option>
          <option value="6">۶ ماهه</option>
          <option value="12" selected>۱ ساله (۱۲ ماهه)</option>
        </select>
      </div>

      <div style="margin-bottom:18px;padding-top:10px;border-top:1px solid #e2e8f0">
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px">پلن اشتراک شرکت:</label>
        <select name="subscription_plan" id="selectSubscriptionPlan" style="width:100%;border:1px solid #cbd5e1;padding:8px;border-radius:8px">
          <option value="">بدون اشتراک</option>
          <option value="12m">اشتراک ۱ ساله (اولویت ۴)</option>
          <option value="6m">اشتراک ۶ ماهه (اولویت ۵)</option>
          <option value="3m">اشتراک ۳ ماهه (اولویت ۶)</option>
        </select>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:8px">
        <button type="button" class="btn ghost small" onclick="closeTierModal()">انصراف</button>
        <button type="submit" class="btn small" style="background:#f59e0b;color:#000;font-weight:700">ذخیره تنظیمات</button>
      </div>
    </form>
  </div>
</div>

<!-- مودال یادداشت داخلی همکاران -->
<div id="noteModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:999;align-items:center;justify-content:center">
  <div style="background:#fff;border-radius:12px;padding:24px;max-width:460px;width:92%;box-shadow:0 10px 25px rgba(0,0,0,0.2)">
    <h3 style="margin:0 0 8px;font-size:16px;color:#0f172a" id="noteModalTitle">یادداشت داخلی شرکت</h3>
    <p style="margin:0 0 12px;font-size:12.5px;color:#64748b">این یادداشت فقط برای تیم مدیریت قابل مشاهده است و در سایت نمایش داده نمی‌شود.</p>
    <form id="noteForm" method="post" action="">
      @csrf
      <textarea name="admin_note" id="noteTextarea" rows="4" placeholder="مثلاً: با مدیرعامل تماس گرفته شد، منتظر ارسال لوگو..." style="width:100%;padding:10px;border:1px solid #cbd5e1;border-radius:8px;box-sizing:border-box;margin-bottom:14px"></textarea>
      <div style="display:flex;justify-content:flex-end;gap:8px">
        <button type="button" class="btn ghost small" onclick="closeNoteModal()">انصراف</button>
        <button type="submit" class="btn small" style="background:#0284c7;color:#fff;font-weight:700">ذخیره یادداشت</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
function openTierModal(companyId, companyName, currentTier, currentSub) {
 const modal = document.getElementById('tierModal');
 const title = document.getElementById('modalCompanyName');
 const form = document.getElementById('tierForm');
 const select = document.getElementById('selectTier');
 const subSelect = document.getElementById('selectSubscriptionPlan');

 title.textContent = 'تنظیم ویژه و اشتراک: ' + companyName;
 form.action = '{{ url("/admin/companies") }}/' + companyId + '/tier';
 select.value = currentTier || '';
 if (subSelect) subSelect.value = currentSub || '';
 modal.style.display = 'flex';
}

function closeTierModal() {
 document.getElementById('tierModal').style.display = 'none';
}

function openNoteModal(companyId, companyName, currentNote) {
  document.getElementById('noteModalTitle').textContent = 'یادداشت داخلی: ' + companyName;
  document.getElementById('noteForm').action = '{{ url("/admin/companies") }}/' + companyId + '/note';
  document.getElementById('noteTextarea').value = currentNote || '';
  document.getElementById('noteModal').style.display = 'flex';
}
function closeNoteModal() {
  document.getElementById('noteModal').style.display = 'none';
}
</script>
@endpush
@endsection
