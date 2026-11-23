@extends('layouts.app', ['title' => 'داشبورد کاربری', 'dashboardNav' => true])

@section('content')
<div class="dashboard-layout">
  <!-- سایدبار منو (ستون راست در RTL) -->
  <aside class="dashboard-sidebar-card">
    <nav class="dashboard-menu">
      <a href="javascript:void(0)" onclick="switchTab('dashboard')" id="nav-dashboard" class="dashboard-menu-link active">
        <span>داشبورد</span>
      </a>
      <a href="javascript:void(0)" onclick="switchTab('companies')" id="nav-companies" class="dashboard-menu-link">
        <span>شرکت‌های من</span>
        @if($companies->count() > 0)
          <span class="badge" style="background:#eff6ff;color:var(--primary);font-size:11px;padding:2px 7px">{{ $companies->count() }}</span>
        @endif
      </a>
      <a href="javascript:void(0)" onclick="switchTab('jobs')" id="nav-jobs" class="dashboard-menu-link">
        <span>آگهی‌های استخدام</span>
      </a>
      <a href="javascript:void(0)" onclick="switchTab('resumes')" id="nav-resumes" class="dashboard-menu-link">
        <span>رزومه‌ها</span>
      </a>
      <a href="javascript:void(0)" onclick="switchTab('alerts')" id="nav-alerts" class="dashboard-menu-link">
        <span>گوش‌به‌زنگ</span>
      </a>
      <a href="javascript:void(0)" onclick="switchTab('subscription')" id="nav-subscription" class="dashboard-menu-link">
        <span>اشتراک و پرداخت</span>
      </a>
      <a href="javascript:void(0)" onclick="switchTab('messages')" id="nav-messages" class="dashboard-menu-link">
        <span>پیام‌ها و اعلان‌ها</span>
      </a>
    </nav>
  </aside>

  <!-- محتوای اصلی (ستون چپ در RTL) -->
  <div>
    <!-- تب ۱: داشبورد اصلی (دقیقاً مطابق طرح ارسالی) -->
    <div id="tab-dashboard" class="dashboard-tab-content">
      
      <!-- بنرهای اعلان و هشدار -->
      <div class="dash-alert dash-alert-warn" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px">
        <span>اشتراک نقره‌ای شما ۶ روز دیگر منقضی می‌شود.</span>
        <a href="javascript:void(0)" onclick="switchTab('subscription')" style="color:#2563eb;font-weight:600;text-decoration:none">تمدید</a>
      </div>

      <div class="dash-alert dash-alert-success">
        @php
          $approvedCompany = $companies->where('status', 'approved')->first();
        @endphp
        @if($approvedCompany)
          شرکت «{{ $approvedCompany->name }}» تأیید و منتشر شد.
        @else
          شرکت «کاغذسازی آریا» تأیید و منتشر شد.
        @endif
      </div>

      <div class="dash-alert dash-alert-danger">
        @php
          $rejectedCompany = $companies->where('status', 'rejected')->first();
        @endphp
        @if($rejectedCompany)
          شرکت «{{ $rejectedCompany->name }}»: لوگو نامناسب است، لطفاً ویرایش کنید.
        @else
          شرکت «بسته‌بندی پارس»: لوگو نامناسب است، لطفاً ویرایش کنید.
        @endif
      </div>

      <!-- کارت‌های آمار ۴ تایی -->
      <div class="dash-stat-grid">
        <div class="dash-stat-box">
          <div class="dash-stat-title">شرکت‌ها</div>
          <div class="dash-stat-number">{{ $companies->count() ?: 2 }}</div>
        </div>
        <div class="dash-stat-box">
          <div class="dash-stat-title">آگهی فعال</div>
          <div class="dash-stat-number">۲</div>
        </div>
        <div class="dash-stat-box">
          <div class="dash-stat-title">رزومه دریافتی</div>
          <div class="dash-stat-number">۱۴</div>
        </div>
        <div class="dash-stat-box">
          <div class="dash-stat-title">بازدید ماه</div>
          <div class="dash-stat-number">۱,۲۴۰</div>
        </div>
      </div>

      <!-- مدیریت شرکت‌ها (دسترسی سریع و ثبت شرکت جدید) -->
      <div class="dash-table-card">
        <div style="display:flex;justify-content:space-between;align-items:center;padding:14px 18px;border-bottom:1px solid var(--border);background:#fff">
          <h3 style="margin:0;font-size:15.5px;color:var(--text);font-weight:700">شرکت‌های من</h3>
          <a class="btn small" href="{{ route('companies.create') }}" style="font-size:13px;padding:6px 14px">+ ثبت شرکت جدید</a>
        </div>
        @if($companies->count() > 0)
          <table class="dash-table">
            <thead>
              <tr>
                <th>نام شرکت</th>
                <th>دسته‌بندی اصلی</th>
                <th style="text-align:center">وضعیت</th>
                <th style="text-align:left">عملیات</th>
              </tr>
            </thead>
            <tbody>
              @foreach($companies as $c)
                <tr>
                  <td>
                    <b style="color:var(--text);font-size:14px">{{ $c->name }}</b>
                    @if($c->brand_name)
                      <span style="color:var(--muted);font-size:12px;margin-right:4px">({{ $c->brand_name }})</span>
                    @endif
                  </td>
                  <td style="color:#64748b;font-size:13px">{{ $c->category->name ?? '—' }}</td>
                  <td style="text-align:center">
                    @if($c->status === 'approved')
                      <span class="badge ok" style="padding:4px 10px;font-size:12px">تأییدشده</span>
                    @elseif($c->status === 'rejected')
                      <span class="badge bad" style="padding:4px 10px;font-size:12px">نیاز به ویرایش</span>
                    @else
                      <span class="badge warn" style="padding:4px 10px;font-size:12px">در انتظار بررسی</span>
                    @endif
                  </td>
                  <td style="text-align:left">
                    <div style="display:inline-flex;gap:6px">
                      <a class="btn ghost small" href="{{ route('companies.edit', $c) }}" style="padding:3px 12px;font-size:12px">ویرایش</a>
                      @if($c->status === 'approved')
                        <a class="btn ghost small" href="{{ route('company', $c) }}" target="_blank" style="padding:3px 12px;font-size:12px">مشاهده</a>
                      @endif
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @else
          <div style="padding:24px;text-align:center;color:var(--muted)">
            <p style="margin:0 0 12px;font-size:14px">هنوز شرکتی ثبت نکرده‌اید. با کلیک بر روی دکمه‌ی زیر اطلاعات اولین شرکت یا کارخانه خود را ثبت کنید.</p>
            <a class="btn small" href="{{ route('companies.create') }}">ثبت شرکت جدید</a>
          </div>
        @endif
      </div>

      <!-- گوش‌به‌زنگ‌های من -->
      <h3 style="font-size:16px;font-weight:700;margin:28px 0 12px;color:#1e293b">گوش‌به‌زنگ‌های من</h3>
      <div class="dash-table-card">
        <table class="dash-table">
          <thead>
            <tr>
              <th>عنوان</th>
              <th>شرایط</th>
              <th style="text-align:center">پیامک</th>
              <th style="text-align:left"></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td style="font-weight:600">اپراتور چاپ</td>
              <td style="color:#475569">دسته: چاپ · شهر: تهران</td>
              <td style="text-align:center">
                <span class="badge ok" style="padding:4px 12px;font-size:12px">فعال</span>
              </td>
              <td style="text-align:left">
                <button type="button" class="btn ghost small" style="border-radius:6px;padding:3px 12px;font-size:12px">ویرایش</button>
              </td>
            </tr>
            <tr>
              <td style="font-weight:600">کارشناس فروش</td>
              <td style="color:#475569">کلیدواژه: کارتن</td>
              <td style="text-align:center">
                <span class="badge warn" style="padding:4px 12px;font-size:12px;background:#fef3c7;color:#92400e">متوقف</span>
              </td>
              <td style="text-align:left">
                <button type="button" class="btn ghost small" style="border-radius:6px;padding:3px 12px;font-size:12px">فعال‌سازی</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- تاریخچه پرداخت -->
      <h3 style="font-size:16px;font-weight:700;margin:28px 0 12px;color:#1e293b">تاریخچه پرداخت</h3>
      <div class="dash-table-card">
        <table class="dash-table">
          <thead>
            <tr>
              <th>تاریخ</th>
              <th>شرح</th>
              <th>مبلغ</th>
              <th style="text-align:center">وضعیت</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td style="color:#475569">۱۴۰۵/۰۸/۰۱</td>
              <td style="font-weight:600">اشتراک نقره‌ای</td>
              <td style="font-weight:700">۵,۰۰۰,۰۰۰ تومان</td>
              <td style="text-align:center">
                <span class="badge ok" style="padding:4px 12px;font-size:12px">موفق</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>

    <!-- تب ۲: شرکت‌های من -->
    <div id="tab-companies" class="dashboard-tab-content" style="display:none">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
        <h2 style="margin:0;font-size:18px">شرکت‌های من</h2>
        <a class="btn" href="{{ route('companies.create') }}">+ ثبت شرکت جدید</a>
      </div>
      <div class="dash-table-card">
        @if($companies->count() > 0)
          <table class="dash-table">
            <thead>
              <tr>
                <th>نام شرکت</th>
                <th>دسته‌بندی اصلی</th>
                <th>شهر</th>
                <th style="text-align:center">وضعیت</th>
                <th style="text-align:left">عملیات</th>
              </tr>
            </thead>
            <tbody>
              @foreach($companies as $c)
                <tr>
                  <td>
                    <b>{{ $c->name }}</b>
                    @if($c->brand_name)<span style="color:var(--muted);font-size:12px"> ({{ $c->brand_name }})</span>@endif
                  </td>
                  <td>{{ $c->category->name ?? '—' }}</td>
                  <td>{{ $c->city->name ?? '—' }}</td>
                  <td style="text-align:center">
                    @if($c->status === 'approved')
                      <span class="badge ok">تأییدشده</span>
                    @elseif($c->status === 'rejected')
                      <span class="badge bad">ردشده</span>
                    @else
                      <span class="badge warn">در انتظار بررسی</span>
                    @endif
                  </td>
                  <td style="text-align:left">
                    <a class="btn ghost small" href="{{ route('companies.edit', $c) }}">ویرایش</a>
                    @if($c->status === 'approved')
                      <a class="btn ghost small" href="{{ route('company', $c) }}" target="_blank">مشاهده</a>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @else
          <div style="padding:32px;text-align:center;color:var(--muted)">
            <p style="margin:0 0 14px">هنوز هیچ شرکتی توسط شما ثبت نشده است.</p>
            <a class="btn" href="{{ route('companies.create') }}">ثبت اولین شرکت</a>
          </div>
        @endif
      </div>
    </div>

    <!-- تب ۳: آگهی‌های استخدام -->
    <div id="tab-jobs" class="dashboard-tab-content" style="display:none">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
        <h2 style="margin:0;font-size:18px">آگهی‌های استخدام</h2>
        <button type="button" class="btn" onclick="alert('بخش آگهی‌های استخدام به زودی فعال خواهد شد.')">+ ثبت آگهی جدید</button>
      </div>
      <div class="dash-table-card" style="padding:32px;text-align:center;color:var(--muted)">
        <p style="margin:0;font-size:14.5px">شما در حال حاضر ۲ آگهی فعال دارید (اپراتور چاپ و کارشناس فروش).</p>
      </div>
    </div>

    <!-- تب ۴: رزومه‌ها -->
    <div id="tab-resumes" class="dashboard-tab-content" style="display:none">
      <h2 style="margin:0 0 16px;font-size:18px">رزومه‌های دریافتی</h2>
      <div class="dash-table-card" style="padding:32px;text-align:center;color:var(--muted)">
        <p style="margin:0;font-size:14.5px">تاکنون ۱۴ رزومه برای آگهی‌های شما ارسال شده است.</p>
      </div>
    </div>

    <!-- تب ۵: گوش‌به‌زنگ -->
    <div id="tab-alerts" class="dashboard-tab-content" style="display:none">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
        <h2 style="margin:0;font-size:18px">گوش‌به‌زنگ‌های من</h2>
        <button type="button" class="btn small" onclick="alert('ایجاد گوش‌به‌زنگ جدید به زودی فعال خواهد شد.')">+ ایجاد گوش‌به‌زنگ جدید</button>
      </div>
      <div class="dash-table-card">
        <table class="dash-table">
          <thead>
            <tr>
              <th>عنوان</th>
              <th>شرایط</th>
              <th style="text-align:center">پیامک</th>
              <th style="text-align:left"></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td style="font-weight:600">اپراتور چاپ</td>
              <td style="color:#475569">دسته: چاپ · شهر: تهران</td>
              <td style="text-align:center"><span class="badge ok">فعال</span></td>
              <td style="text-align:left"><button class="btn ghost small">ویرایش</button></td>
            </tr>
            <tr>
              <td style="font-weight:600">کارشناس فروش</td>
              <td style="color:#475569">کلیدواژه: کارتن</td>
              <td style="text-align:center"><span class="badge warn">متوقف</span></td>
              <td style="text-align:left"><button class="btn ghost small">فعال‌سازی</button></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- تب ۶: اشتراک و پرداخت -->
    <div id="tab-subscription" class="dashboard-tab-content" style="display:none">
      <h2 style="margin:0 0 16px;font-size:18px">اشتراک و صورتحساب</h2>
      <div class="dash-alert dash-alert-warn" style="margin-bottom:16px">
        <b>اشتراک فعلی: نقره‌ای</b> — اعتبار باقی‌مانده: ۶ روز (انقضا: ۱۴۰۵/۰۸/۲۶)
      </div>
      <div class="dash-table-card">
        <div style="padding:14px 18px;border-bottom:1px solid var(--border)">
          <h3 style="margin:0;font-size:15px;color:var(--text)">تاریخچه پرداخت</h3>
        </div>
        <table class="dash-table">
          <thead>
            <tr>
              <th>تاریخ</th>
              <th>شرح</th>
              <th>مبلغ</th>
              <th style="text-align:center">وضعیت</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td style="color:#475569">۱۴۰۵/۰۸/۰۱</td>
              <td style="font-weight:600">اشتراک نقره‌ای</td>
              <td style="font-weight:700">۵,۰۰۰,۰۰۰ تومان</td>
              <td style="text-align:center"><span class="badge ok">موفق</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- تب ۷: پیام‌ها و اعلان‌ها -->
    <div id="tab-messages" class="dashboard-tab-content" style="display:none">
      <h2 style="margin:0 0 16px;font-size:18px">پیام‌ها و اعلان‌ها</h2>
      <div class="dash-table-card" style="padding:20px">
        <div class="dash-alert dash-alert-success" style="margin-bottom:10px">
          شرکت «کاغذسازی آریا» با موفقیت توسط تیم نظارت تأیید و در دایرکتوری منتشر شد.
        </div>
        <div class="dash-alert dash-alert-danger" style="margin-bottom:0">
          شرکت «بسته‌بندی پارس»: تصویر لوگوی ارسال‌شده کیفیت کافی ندارد، لطفاً فایل با کیفیت بالاتر بارگذاری نمایید.
        </div>
      </div>
    </div>

  </div>
</div>

<script>
function switchTab(tabId) {
  // Update sidebar active classes
  document.querySelectorAll('.dashboard-menu-link').forEach(link => {
    link.classList.remove('active');
  });
  const currentLink = document.getElementById('nav-' + tabId);
  if (currentLink) currentLink.classList.add('active');

  // Switch tab content
  document.querySelectorAll('.dashboard-tab-content').forEach(content => {
    content.style.display = 'none';
  });
  const target = document.getElementById('tab-' + tabId);
  if (target) {
    target.style.display = 'block';
  }

  // Update hash in URL
  if (history.pushState) {
    history.pushState(null, null, '#' + tabId);
  }
}

// Check URL hash on page load
document.addEventListener('DOMContentLoaded', function() {
  const hash = window.location.hash.replace('#', '');
  if (hash && document.getElementById('tab-' + hash)) {
    switchTab(hash);
  }
});
</script>
@endsection
