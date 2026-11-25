@extends('layouts.app', ['title' => 'داشبورد کاربری', 'dashboardNav' => true])

@section('content')
<div class="dashboard-layout">
  <!-- سایدبار منو (ستون راست در RTL) -->
  <aside class="dashboard-sidebar-card">
    @php $dashUser = auth()->user(); @endphp
    <div style="padding:12px 12px 14px;border-bottom:1px solid var(--border);margin-bottom:8px">
      <div style="display:flex;align-items:center;gap:10px">
        <div style="width:40px;height:40px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:15px;flex-shrink:0">
          {{ mb_substr($dashUser->name ?: $dashUser->phone, 0, 1) }}
        </div>
        <div style="min-width:0;flex:1">
          <div style="font-size:13.5px;font-weight:800;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
            {{ $dashUser->name ?: 'کاربر گرامی' }}
          </div>
          <div style="font-size:11.5px;color:var(--muted);direction:ltr;text-align:right">{{ $dashUser->phone }}</div>
        </div>
      </div>
      @if($dashUser->isAdmin())
        <div style="margin-top:8px">
          <span style="display:inline-block;font-size:11px;font-weight:700;padding:2px 8px;border-radius:99px;background:#fef3c7;color:#92400e">{{ $dashUser->roleLabel() }}</span>
        </div>
      @endif
      <div style="margin-top:8px">
        <button type="button" onclick="const f=document.getElementById('dashProfileForm');f.style.display=f.style.display==='none'?'block':'none';" style="background:none;border:none;padding:0;font-size:11.5px;color:var(--primary);cursor:pointer;font-family:inherit;font-weight:600">
          {!! svg_icon('edit', 12) !!} {{ $dashUser->name ? 'ویرایش نام' : 'ثبت نام و نام خانوادگی' }}
        </button>
        <form id="dashProfileForm" method="POST" action="{{ route('dashboard.profile') }}" style="display:none;margin-top:8px">
          @csrf
          <div style="display:flex;gap:6px">
            <input type="text" name="name" value="{{ $dashUser->name }}" placeholder="نام و نام خانوادگی" required style="flex:1;min-width:0;padding:5px 8px;font-size:12px;border-radius:7px">
            <button type="submit" class="btn small" style="padding:5px 10px;font-size:11.5px">ثبت</button>
          </div>
        </form>
      </div>
    </div>

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
      <a href="javascript:void(0)" onclick="switchTab('saved')" id="nav-saved" class="dashboard-menu-link">
        <span>شرکت‌های ذخیره‌شده</span>
        <span id="saved-badge-count" class="badge" style="background:#fef3c7;color:#92400e;font-size:11px;padding:2px 7px;{{ $savedCompanies->count() > 0 ? '' : 'display:none' }}">{{ $savedCompanies->count() }}</span>
      </a>
      <a href="javascript:void(0)" onclick="switchTab('jobs')" id="nav-jobs" class="dashboard-menu-link">
        <span>آگهی‌های استخدام</span>
        @if($jobs->count() > 0)
          <span class="badge" style="background:#eff6ff;color:var(--primary);font-size:11px;padding:2px 7px">{{ $jobs->count() }}</span>
        @endif
      </a>
      <a href="javascript:void(0)" onclick="switchTab('resumes')" id="nav-resumes" class="dashboard-menu-link">
        <span>رزومه‌ها</span>
        @if($totalApplications > 0)
          <span class="badge" style="background:#f0fdf4;color:#166534;font-size:11px;padding:2px 7px">{{ $totalApplications }}</span>
        @endif
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
      @if(auth()->user()->isAdmin())
        <hr style="margin:10px 0;border:none;border-top:1px solid #e2e8f0">
        <a href="{{ route('admin.dashboard') }}" class="dashboard-menu-link" style="background:#eff6ff;color:#0369a1;font-weight:700">
          <span>{!! svg_icon('crown', 15) !!} پنل مدیریت کل سایت</span>
        </a>
      @endif
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
        <div class="dash-stat-box" onclick="switchTab('companies')" style="cursor:pointer">
          <div class="dash-stat-title">شرکت‌های من</div>
          <div class="dash-stat-number">{{ $companies->count() ?: 2 }}</div>
        </div>
        <div class="dash-stat-box" onclick="switchTab('saved')" style="cursor:pointer" title="مشاهده شرکت‌های ذخیره‌شده">
          <div class="dash-stat-title">شرکت‌های ذخیره‌شده</div>
          <div class="dash-stat-number" style="color:#0284c7">{{ $savedCompanies->count() }}</div>
        </div>
        <div class="dash-stat-box" onclick="switchTab('jobs')" style="cursor:pointer">
          <div class="dash-stat-title">آگهی فعال</div>
          <div class="dash-stat-number">{{ $jobs->where('status', 'active')->count() ?: 2 }}</div>
        </div>
        <div class="dash-stat-box" onclick="switchTab('resumes')" style="cursor:pointer">
          <div class="dash-stat-title">رزومه دریافتی</div>
          <div class="dash-stat-number">{{ $totalApplications ?: 14 }}</div>
        </div>
      </div>

      @if($companies->count() > 0 && isset($ownerAnalytics))
      <!-- آمار بازدید و تعامل مشتریان -->
      <div class="dash-table-card" style="padding:18px 20px;margin-bottom:24px">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;margin-bottom:16px">
          <div>
            <h3 style="margin:0 0 4px;font-size:15.5px;color:var(--text);font-weight:700">{!! svg_icon('bar-chart', 16) !!} آمار بازدید و تعامل مشتریان</h3>
            <span style="font-size:12.5px;color:var(--muted)">گزارش عملکرد صفحه‌های شرکت شما در ۳۰ روز گذشته</span>
          </div>
          <div style="display:flex;gap:12px;flex-wrap:wrap;font-size:12.5px">
            <span style="padding:5px 10px;border-radius:8px;background:#eff6ff;color:#0369a1;font-weight:700">امروز: {{ number_format($ownerAnalytics['views_today']) }}</span>
            <span style="padding:5px 10px;border-radius:8px;background:#f0fdf4;color:#15803d;font-weight:700">۷ روز: {{ number_format($ownerAnalytics['views_7d']) }}</span>
            <span style="padding:5px 10px;border-radius:8px;background:#fef3c7;color:#92400e;font-weight:700">۳۰ روز: {{ number_format($ownerAnalytics['views_30d']) }}</span>
          </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:16px" class="dash-lead-grid">
          <div style="padding:10px 12px;border-radius:10px;background:#f8fafc;border:1px solid var(--border)">
            <span style="font-size:12px;color:var(--muted);display:block">کلیک شماره تماس</span>
            <b style="font-size:18px;color:#0284c7">{{ number_format($ownerAnalytics['phone_clicks']) }}</b>
          </div>
          <div style="padding:10px 12px;border-radius:10px;background:#f8fafc;border:1px solid var(--border)">
            <span style="font-size:12px;color:var(--muted);display:block">کلیک واتساپ</span>
            <b style="font-size:18px;color:#16a34a">{{ number_format($ownerAnalytics['whatsapp_clicks']) }}</b>
          </div>
          <div style="padding:10px 12px;border-radius:10px;background:#f8fafc;border:1px solid var(--border)">
            <span style="font-size:12px;color:var(--muted);display:block">کلیک وب‌سایت</span>
            <b style="font-size:18px;color:#6366f1">{{ number_format($ownerAnalytics['website_clicks']) }}</b>
          </div>
          <div style="padding:10px 12px;border-radius:10px;background:#f8fafc;border:1px solid var(--border)">
            <span style="font-size:12px;color:var(--muted);display:block">ذخیره توسط کاربران</span>
            <b style="font-size:18px;color:#d97706">{{ number_format($ownerAnalytics['bookmark_clicks']) }}</b>
          </div>
        </div>

        <div style="position:relative;height:210px">
          <canvas id="ownerAnalyticsChart"></canvas>
        </div>
      </div>
      @endif

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
                    @if($c->status === 'approved')
                      <a href="{{ $c->url }}" target="_blank" style="font-weight:700;color:var(--text);font-size:14px;text-decoration:none">{{ $c->name }}</a>
                    @else
                      <b style="color:var(--text);font-size:14px">{{ $c->name }}</b>
                    @endif
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
                    <div class="gear-dropdown">
                      <button type="button" class="gear-btn" onclick="toggleGearMenu(this, event)" title="عملیات">
                        {!! svg_icon('settings', 16) !!}
                      </button>
                      <div class="gear-dropdown-menu">
                        <a class="gear-dropdown-item" href="{{ route('companies.edit', $c) }}">
                          {!! svg_icon('edit', 14) !!} ویرایش
                        </a>
                        <form method="POST" action="{{ route('companies.destroy', $c) }}" style="margin:0" onsubmit="return confirm('آیا از حذف شرکت «{{ addslashes($c->name) }}» اطمینان دارید؟')">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="gear-dropdown-item danger">
                            {!! svg_icon('trash', 14) !!} حذف
                          </button>
                        </form>
                      </div>
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
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;flex-wrap:wrap;gap:8px">
        <h2 style="margin:0;font-size:18px">شرکت‌های من</h2>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
          <a class="btn" href="{{ route('companies.create') }}">+ ثبت شرکت جدید</a>
        </div>
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
                    @if($c->status === 'approved')
                      <a href="{{ $c->url }}" target="_blank" style="font-weight:700;color:var(--text);text-decoration:none">{{ $c->name }}</a>
                    @else
                      <b>{{ $c->name }}</b>
                    @endif
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
                    <div class="gear-dropdown">
                      <button type="button" class="gear-btn" onclick="toggleGearMenu(this, event)" title="عملیات">
                        {!! svg_icon('settings', 16) !!}
                      </button>
                      <div class="gear-dropdown-menu">
                        <a class="gear-dropdown-item" href="{{ route('companies.edit', $c) }}">
                          {!! svg_icon('edit', 14) !!} ویرایش
                        </a>
                        <form method="POST" action="{{ route('companies.destroy', $c) }}" style="margin:0" onsubmit="return confirm('آیا از حذف شرکت «{{ addslashes($c->name) }}» اطمینان دارید؟')">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="gear-dropdown-item danger">
                            {!! svg_icon('trash', 14) !!} حذف
                          </button>
                        </form>
                      </div>
                    </div>
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
        <h2 style="margin:0;font-size:18px">آگهی‌های استخدام من</h2>
        <a href="{{ route('employer.jobs.create') }}" class="btn">+ درج آگهی استخدام جدید</a>
      </div>

      <div class="dash-table-card">
        @if($jobs->count() > 0)
          <table class="dash-table">
            <thead>
              <tr>
                <th>عنوان آگهی شغلی</th>
                <th>شرکت / محل</th>
                <th style="text-align:center">رزومه‌ها</th>
                <th style="text-align:center">وضعیت</th>
                <th style="text-align:left">عملیات</th>
              </tr>
            </thead>
            <tbody>
              @foreach($jobs as $job)
                <tr>
                  <td>
                    <a href="{{ route('jobs.show', $job) }}" target="_blank" style="font-weight:700;color:var(--text);text-decoration:none">
                      {{ $job->title }}
                    </a>
                    @if($job->is_immediate)
                      <span class="badge bad" style="font-size:11px;margin-right:4px">فوری</span>
                    @endif
                  </td>
                  <td style="color:#64748b;font-size:13px">
                    {{ $job->company->name ?? 'مستقل' }}
                    · {{ $job->province->name ?? '' }}
                  </td>
                  <td style="text-align:center">
                    <a href="{{ route('employer.jobs.applications', $job) }}" class="badge ok" style="padding:4px 10px;text-decoration:none">
                      {!! svg_icon('users', 15) !!} {{ $job->applications->count() }} رزومه
                    </a>
                  </td>
                  <td style="text-align:center">
                    @if($job->status === 'active')
                      <span class="badge ok">فعال</span>
                    @else
                      <span class="badge silver">متوقف</span>
                    @endif
                  </td>
                  <td style="text-align:left">
                    <div style="display:inline-flex;gap:6px;align-items:center">
                      <a href="{{ route('employer.jobs.applications', $job) }}" class="btn small" style="background:#0284c7;font-size:12px;padding:3px 10px">
                        رزومه‌ها ({{ $job->applications->count() }})
                      </a>
                      <a href="{{ route('employer.jobs.edit', $job) }}" class="btn ghost small" style="font-size:12px;padding:3px 10px">ویرایش</a>
                      <form method="post" action="{{ route('employer.jobs.toggle', $job) }}" style="display:inline">
                        @csrf
                        <button type="submit" class="btn ghost small" style="font-size:12px;padding:3px 8px" title="تغییر وضعیت فعال/متوقف">
                          {{ $job->status === 'active' ? 'توقف' : 'فعال‌سازی' }}
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @else
          <div style="padding:40px;text-align:center;color:var(--muted)">
            <span style="font-size:36px;display:block;margin-bottom:8px">{!! svg_icon('megaphone', 15) !!}</span>
            <p style="margin:0 0 14px;font-size:14.5px">هنوز هیچ آگهی استخدامی ثبت نکرده‌اید.</p>
            <a href="{{ route('employer.jobs.create') }}" class="btn">+ درج اولین آگهی استخدام</a>
          </div>
        @endif
      </div>
    </div>

    <!-- تب ۴: رزومه‌ها -->
    <div id="tab-resumes" class="dashboard-tab-content" style="display:none">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
        <h2 style="margin:0;font-size:18px">رزومه‌های دریافتی</h2>
        <span style="font-size:13px;color:var(--muted)">مجموع رزومه‌ها: <b>{{ $totalApplications }}</b></span>
      </div>

      <div class="dash-table-card">
        @if($recentApplications->count() > 0)
          <table class="dash-table">
            <thead>
              <tr>
                <th>نام متقاضی</th>
                <th>برای شغل</th>
                <th>شماره تماس</th>
                <th>سابقه / حقوق درخواستی</th>
                <th style="text-align:center">وضعیت</th>
                <th style="text-align:left">فایل / جزئیات</th>
              </tr>
            </thead>
            <tbody>
              @foreach($recentApplications as $app)
                <tr>
                  <td>
                    <b>{{ $app->full_name }}</b>
                    <span style="display:block;font-size:11.5px;color:var(--muted)">{{ $app->created_at->diffForHumans() }} · {!! svg_icon('eye', 15) !!} {{ number_format($app->views_count ?? 0) }} بازدید</span>
                  </td>
                  <td>
                    <a href="{{ route('jobs.show', $app->jobPosting) }}" target="_blank" style="color:var(--primary);text-decoration:none">
                      {{ $app->jobPosting->title ?? '—' }}
                    </a>
                  </td>
                  <td>
                    <a href="tel:{{ $app->phone }}" style="direction:ltr;display:inline-block;font-weight:600">{{ $app->phone }}</a>
                  </td>
                  <td style="font-size:12.5px;color:#475569">
                    سابقه: {{ $app->experience_years ?: '—' }}
                    <span style="display:block;color:#16a34a">حقوق: {{ $app->expected_salary ?: 'توافقی' }}</span>
                  </td>
                  <td style="text-align:center">
                    <span class="badge {{ $app->status_badge_class }}" style="font-size:12px">
                      {{ $app->status_label }}
                    </span>
                  </td>
                  <td style="text-align:left">
                    <div style="display:inline-flex;gap:6px">
                      @if($app->resume_file)
                        <a href="{{ asset($app->resume_file) }}" target="_blank" class="btn small" style="background:#0284c7;font-size:11.5px;padding:3px 8px">
                          دانلود PDF
                        </a>
                      @endif
                      <a href="{{ route('employer.jobs.applications', $app->jobPosting) }}" class="btn ghost small" style="font-size:11.5px;padding:3px 8px">
                        بررسی و یادداشت
                      </a>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @else
          <div style="padding:40px;text-align:center;color:var(--muted)">
            <span style="font-size:36px;display:block;margin-bottom:8px">{!! svg_icon('inbox', 15) !!}</span>
            <p style="margin:0 0 10px;font-size:14.5px">هنوز رزومه‌ای برای آگهی‌های شما ثبت نشده است.</p>
            <p style="margin:0;font-size:13px">با درج آگهی‌های جدید، رزومه‌های کارجویان در این بخش نمایش داده خواهد شد.</p>
          </div>
        @endif
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
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;flex-wrap:wrap;gap:10px">
        <div>
          <h2 style="margin:0 0 4px;font-size:18px">اشتراک و ویژه کردن شرکت</h2>
          <p style="margin:0;font-size:13px;color:var(--muted)">مدیریت اشتراک‌های (۳ ماهه، ۶ ماهه، ۱ ساله) و جایگاه ویژه تبلیغاتی (برنزی، نقره‌ای، طلایی)</p>
        </div>
        <div style="display:flex;gap:8px">
          <a href="{{ route('subscription.fee') }}" target="_blank" class="btn ghost small" style="font-size:12px">
            تعرفه اشتراک‌ها ↗
          </a>
          <a href="{{ route('advertising') }}" target="_blank" class="btn ghost small" style="font-size:12px">
            پلن‌های ویژه و تبلیغات ↗
          </a>
        </div>
      </div>

      <!-- وضعیت اشتراک و ویژه شرکت‌های کاربر -->
      @if($companies->count() > 0)
        <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:24px">
          @foreach($companies as $c)
            <div class="card" style="padding:16px 20px;background:#fff;border-radius:10px;border:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">
              <div>
                <b style="font-size:15px;color:var(--text)">{{ $c->name }}</b>
                <div style="font-size:12.5px;color:var(--muted);margin-top:6px;display:flex;gap:8px;flex-wrap:wrap;align-items:center">
                  <span>وضعیت ویژه:</span>
                  @if($c->activeTier() === 'gold')
                    <span class="badge gold" style="font-size:11px">{!! svg_icon('star-filled', 14) !!} ویژه طلایی</span>
                  @elseif($c->activeTier() === 'silver')
                    <span class="badge silver" style="font-size:11px">{!! svg_icon('star-filled', 14) !!} ویژه نقره‌ای</span>
                  @elseif($c->activeTier() === 'bronze')
                    <span class="badge bronze" style="font-size:11px">{!! svg_icon('star-filled', 14) !!} ویژه برنزی</span>
                  @else
                    <span class="badge" style="background:#f1f5f9;color:#64748b;font-size:11px">عادی (بدون ویژه)</span>
                  @endif
                  @if($c->tier_expires_at)
                    <span style="color:#0284c7">تا {{ $c->tier_expires_at->format('Y/m/d') }}</span>
                  @endif

                  <span style="margin-right:8px">اشتراک:</span>
                  @if($c->activeSubscription())
                    <span class="badge ok" style="font-size:11px">{!! svg_icon('check', 14) !!} {{ $c->subscriptionLabel() }}</span>
                    @if($c->subscription_expires_at)
                      <span style="color:#16a34a">تا {{ $c->subscription_expires_at->format('Y/m/d') }}</span>
                    @endif
                  @else
                    <span class="badge" style="background:#f1f5f9;color:#64748b;font-size:11px">بدون اشتراک</span>
                  @endif
                </div>
              </div>

              <div>
                <a href="#upgrade_request_box" onclick="document.getElementById('upgradeCompanySelect').value='{{ $c->id }}'" class="btn small" style="background:var(--primary);font-size:12px">
                  خرید / تمدید یا ویژه کردن
                </a>
              </div>
            </div>
          @endforeach
        </div>

        <!-- فرم درخواست سریع خرید / تمدید اشتراک یا ویژه کردن -->
        <div class="dash-table-card" id="upgrade_request_box" style="padding:18px 20px;margin-bottom:28px">
          <h3 style="margin:0 0 6px;font-size:15.5px;color:var(--text)">درخواست خرید، تمدید اشتراک یا ویژه کردن شرکت</h3>
          <p style="margin:0 0 14px;font-size:12.5px;color:var(--muted)">شرکت و پلن مورد نظر خود را انتخاب کنید تا کارشناسان ۲ کیلو کاغذ برای فعال‌سازی با شما تماس بگیرند.</p>
          <form method="POST" action="{{ route('dashboard.upgrade-request') }}" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:12px;align-items:end">
            @csrf
            <div>
              <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:5px">انتخاب شرکت</label>
              <select name="company_id" id="upgradeCompanySelect" required style="width:100%;padding:8px 10px;border-radius:8px;border:1px solid var(--border)">
                @foreach($companies as $c)
                  <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
              </select>
            </div>
            <div>
              <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:5px">نوع درخواست</label>
              <select name="request_type" id="upgradeRequestType" onchange="syncUpgradePlanOptions()" required style="width:100%;padding:8px 10px;border-radius:8px;border:1px solid var(--border)">
                <option value="subscription">خرید / تمدید اشتراک (۳ تا ۱۲ ماهه)</option>
                <option value="featured">ویژه کردن تبلیغاتی (برنزی، نقره‌ای، طلایی)</option>
              </select>
            </div>
            <div>
              <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:5px">انتخاب پلن</label>
              <select name="plan" id="upgradePlanSelect" required style="width:100%;padding:8px 10px;border-radius:8px;border:1px solid var(--border)">
                <option value="12m">اشتراک ۱ ساله</option>
                <option value="6m">اشتراک ۶ ماهه</option>
                <option value="3m">اشتراک ۳ ماهه</option>
              </select>
            </div>
            <div>
              <button type="submit" class="btn" style="width:100%;padding:9px 14px">ثبت درخواست فعال‌سازی</button>
            </div>
          </form>
        </div>
      @endif

      <!-- ۱. کارت‌های اشتراک (۳ ماهه، ۶ ماهه، ۱ ساله) -->
      <h3 style="margin:0 0 14px;font-size:16px;color:var(--text)" id="plans_grid">۱. پلن‌های اشتراک (امکانات صفحه + رتبه بالاتر)</h3>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px;margin-bottom:28px">

        <div class="card" style="padding:20px;background:#fff;border-radius:12px;border:1px solid var(--border);display:flex;flex-direction:column;justify-content:space-between">
          <div>
            <span style="font-size:12px;color:var(--muted);font-weight:600">دوره کوتاه‌مدت</span>
            <h4 style="margin:4px 0 10px;font-size:16px">اشتراک ۳ ماهه</h4>
            <ul style="list-style:none;padding:0;margin:0;font-size:12.5px;line-height:2;color:#334155">
              <li>{!! svg_icon('check', 15) !!} درج تصاویر و گالری محصولات</li>
              <li>{!! svg_icon('check', 15) !!} افزودن کاتالوگ محصولات (PDF)</li>
              <li>{!! svg_icon('check', 15) !!} حذف تبلیغات رقبا از صفحه شما</li>
              <li>{!! svg_icon('check', 15) !!} بالاتر از شرکت‌های بدون اشتراک</li>
            </ul>
          </div>
          <div style="margin-top:16px">
            <a href="tel:02128421075" class="btn ghost small" style="width:100%;text-align:center;box-sizing:border-box">سفارش: ۰۲۱۲۸۴۲۱۰۷۵</a>
          </div>
        </div>

        <div class="card" style="padding:20px;background:#fff;border-radius:12px;border:1px solid var(--border);display:flex;flex-direction:column;justify-content:space-between">
          <div>
            <span style="font-size:12px;color:var(--muted);font-weight:600">مدت بیشتر با تخفیف</span>
            <h4 style="margin:4px 0 10px;font-size:16px">اشتراک ۶ ماهه</h4>
            <ul style="list-style:none;padding:0;margin:0;font-size:12.5px;line-height:2;color:#334155">
              <li>{!! svg_icon('check', 15) !!} تمام امکانات اشتراک ۳ ماهه</li>
              <li>{!! svg_icon('check', 15) !!} درج آگهی استخدام نامحدود</li>
              <li>{!! svg_icon('check', 15) !!} حذف تبلیغات رقبا از صفحه شما</li>
              <li>{!! svg_icon('check', 15) !!} جایگاه بالاتر از اشتراک ۳ ماهه</li>
            </ul>
          </div>
          <div style="margin-top:16px">
            <a href="tel:02128421075" class="btn ghost small" style="width:100%;text-align:center;box-sizing:border-box">سفارش: ۰۲۱۲۸۴۲۱۰۷۵</a>
          </div>
        </div>

        <div class="card" style="padding:20px;background:#fff;border-radius:12px;border:1.5px solid #0284c7;display:flex;flex-direction:column;justify-content:space-between">
          <div>
            <span style="font-size:12px;color:#0284c7;font-weight:700">{!! svg_icon('check', 15) !!} به‌صرفه‌ترین اشتراک</span>
            <h4 style="margin:4px 0 10px;font-size:16px">اشتراک ۱ ساله</h4>
            <ul style="list-style:none;padding:0;margin:0;font-size:12.5px;line-height:2;color:#334155">
              <li>{!! svg_icon('check', 15) !!} تمام امکانات گالری، کاتالوگ و ویدیو</li>
              <li>{!! svg_icon('check', 15) !!} حذف کامل تبلیغات رقبا</li>
              <li>{!! svg_icon('check', 15) !!} بالاترین اولویت بین اشتراک‌ها</li>
              <li>{!! svg_icon('check', 15) !!} نشان «اشتراک ۱ ساله» در صفحه شرکت</li>
            </ul>
          </div>
          <div style="margin-top:16px">
            <a href="tel:02128421075" class="btn small" style="width:100%;text-align:center;box-sizing:border-box">سفارش اشتراک ۱ ساله</a>
          </div>
        </div>

      </div>

      <!-- ۲. کارت‌های ویژه کردن تبلیغاتی (برنزی، نقره‌ای، طلایی) -->
      <h3 style="margin:0 0 14px;font-size:16px;color:var(--text)">۲. ویژه کردن تبلیغاتی (صدر نتایج جستجو و دسته‌بندی‌ها)</h3>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px;margin-bottom:28px">
        <div class="card" style="padding:20px;background:#fff;border-radius:12px;border:1.5px solid #d97706">
          <h4 style="margin:0 0 8px;font-size:16px;color:#b45309">{!! svg_icon('star-filled', 15) !!} ویژه برنزی</h4>
          <p style="margin:0;font-size:12.5px;color:var(--muted);line-height:1.8">قرارگیری بالاتر از همه اشتراک‌های ۱ ساله، ۶ ماهه، ۳ ماهه و شرکت‌های عادی + نشان برنزی.</p>
        </div>
        <div class="card" style="padding:20px;background:#fff;border-radius:12px;border:1.5px solid #94a3b8">
          <h4 style="margin:0 0 8px;font-size:16px;color:#475569">{!! svg_icon('star-filled', 15) !!} ویژه نقره‌ای</h4>
          <p style="margin:0;font-size:12.5px;color:var(--muted);line-height:1.8">قرارگیری بالاتر از ویژه برنزی و تمام اشتراک‌ها در جستجو و دسته‌بندی‌ها + نشان نقره‌ای.</p>
        </div>
        <div class="card" style="padding:20px;background:#fefce8;border-radius:12px;border:2px solid #eab308">
          <h4 style="margin:0 0 8px;font-size:16px;color:#713f12">{!! svg_icon('star-filled', 15) !!} ویژه طلایی (رتبه ۱)</h4>
          <p style="margin:0;font-size:12.5px;color:#854d0e;line-height:1.8">بالاترین جایگاه ممکن در صفحه اصلی، جستجو و تمام دسته‌بندی‌ها + نشان طلایی اختصاصی.</p>
        </div>
      </div>

      <!-- تاریخچه پرداخت -->
      <div class="dash-table-card">
        <div style="padding:14px 18px;border-bottom:1px solid var(--border)">
          <h3 style="margin:0;font-size:15px;color:var(--text)">تاریخچه تراکنش‌ها و پرداخت‌ها</h3>
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
              <td style="color:#475569">—</td>
              <td style="color:var(--muted)">تراکنشی ثبت نشده است</td>
              <td>—</td>
              <td style="text-align:center"><span class="badge" style="background:#f1f5f9;color:#64748b">—</span></td>
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

    <!-- تب ۸: شرکت‌های ذخیره‌شده -->
    <div id="tab-saved" class="dashboard-tab-content" style="display:none">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;flex-wrap:wrap;gap:10px">
        <div>
          <h2 style="margin:0 0 6px;font-size:18px;color:#1e293b">{!! svg_icon('star-filled', 15) !!} شرکت‌های ذخیره‌شده و برگزیده</h2>
          <p style="margin:0;font-size:13px;color:var(--muted)">شرکت‌ها و کارخانجاتی که برای دسترسی سریع به میز کار خود اضافه کرده‌اید</p>
        </div>
        <a href="{{ route('directory') }}" class="btn ghost small" style="font-size:13px;padding:6px 14px">
          {!! svg_icon('search', 15) !!} جستجو در دایرکتوری شرکت‌ها
        </a>
      </div>

      <div class="dash-table-card">
        @if($savedCompanies->count() > 0)
          <div id="saved-companies-grid" style="display:grid;grid-template-columns:repeat(auto-fill, minmax(280px, 1fr));gap:16px;padding:18px">
            @foreach($savedCompanies as $savedC)
              <div id="saved-card-{{ $savedC->id }}" style="border:1px solid var(--border);border-radius:12px;padding:16px;background:#fff;display:flex;flex-direction:column;justify-content:space-between;transition:box-shadow 0.2s ease">
                <div>
                  <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px">
                    <div style="width:48px;height:48px;border-radius:10px;border:1px solid #e2e8f0;overflow:hidden;background:#f8fafc;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                      @if($savedC->logo)
                        <img src="{{ asset($savedC->logo) }}" alt="{{ $savedC->name }}" style="width:100%;height:100%;object-fit:contain;padding:4px">
                      @else
                        <span style="font-size:20px;font-weight:800;color:var(--primary)">{{ mb_substr($savedC->name, 0, 1) }}</span>
                      @endif
                    </div>
                    <div style="flex:1;min-width:0">
                      <a href="{{ $savedC->url }}" target="_blank" style="text-decoration:none;color:var(--text);font-weight:700;font-size:14.5px;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        {{ $savedC->name }}
                      </a>
                      <div style="font-size:12px;color:var(--muted);margin-top:2px">
                        {{ $savedC->category->name ?? '—' }} · {{ $savedC->city->name ?? '' }}
                      </div>
                    </div>
                  </div>

                  @if($savedC->phone)
                    <div style="font-size:12.5px;color:#475569;margin-bottom:12px;display:flex;align-items:center;gap:6px">
                      <span>{!! svg_icon('phone', 15) !!}</span>
                      <span style="direction:ltr">{{ explode(';', $savedC->phone)[0] }}</span>
                    </div>
                  @endif
                </div>

                <div style="display:flex;gap:8px;align-items:center;padding-top:12px;border-top:1px dashed var(--border)">
                  <a href="{{ $savedC->url }}" target="_blank" class="btn small" style="flex:1;text-align:center;font-size:12px;padding:6px 10px">
                    مشاهده صفحه
                  </a>
                  <button type="button" onclick="removeSavedCompany({{ $savedC->id }})" class="btn ghost small" style="font-size:12px;padding:6px 10px;color:#dc2626;border-color:#fca5a5;cursor:pointer" title="حذف از ذخیره‌ها">
                    حذف
                  </button>
                </div>
              </div>
            @endforeach
          </div>
          <div id="saved-empty-state" style="display:none;padding:40px 20px;text-align:center;color:var(--muted)">
            <div style="font-size:42px;margin-bottom:10px">{!! svg_icon('file-text', 15) !!}</div>
            <p style="margin:0 0 10px;font-size:15px;font-weight:700;color:#334155">هنوز هیچ شرکتی را ذخیره نکرده‌اید</p>
            <p style="margin:0 0 18px;font-size:13.5px;color:#64748b;max-width:480px;margin-left:auto;margin-right:auto">
              هنگام مرور دایرکتوری و مشاهده صفحه هر شرکت، با کلیک روی دکمه «ذخیره در میز کار» می‌توانید شرکت‌های مورد علاقه یا همکاران تجاری خود را اینجا ذخیره کنید.
            </p>
            <a href="{{ route('directory') }}" class="btn small" style="padding:8px 20px;font-size:13.5px">
              مشاهده دایرکتوری شرکت‌ها
            </a>
          </div>
        @else
          <div id="saved-empty-state" style="padding:40px 20px;text-align:center;color:var(--muted)">
            <div style="font-size:42px;margin-bottom:10px">{!! svg_icon('file-text', 15) !!}</div>
            <p style="margin:0 0 10px;font-size:15px;font-weight:700;color:#334155">هنوز هیچ شرکتی را ذخیره نکرده‌اید</p>
            <p style="margin:0 0 18px;font-size:13.5px;color:#64748b;max-width:480px;margin-left:auto;margin-right:auto">
              هنگام مرور دایرکتوری و مشاهده صفحه هر شرکت، با کلیک روی دکمه «ذخیره در میز کار» می‌توانید شرکت‌های مورد علاقه یا همکاران تجاری خود را اینجا ذخیره کنید.
            </p>
            <a href="{{ route('directory') }}" class="btn small" style="padding:8px 20px;font-size:13.5px">
              مشاهده دایرکتوری شرکت‌ها
            </a>
          </div>
        @endif
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

function removeSavedCompany(id) {
 if (!confirm('آیا مایل به حذف این شرکت از لیست ذخیره‌های میز کار هستید؟')) return;

 fetch("/companies/" + id + "/bookmark", {
 method: 'POST',
 headers: {
 'Content-Type': 'application/json',
 'X-CSRF-TOKEN': '{{ csrf_token() }}',
 'Accept': 'application/json'
 }
 })
 .then(res => res.json())
 .then(data => {
 const card = document.getElementById('saved-card-' + id);
 if (card) {
 card.remove();
 }
 const badge = document.getElementById('saved-badge-count');
 if (badge) {
 const current = parseInt(badge.textContent) || 1;
 const next = Math.max(0, current - 1);
 badge.textContent = next;
 if (next === 0) badge.style.display = 'none';
 }
 const grid = document.getElementById('saved-companies-grid');
 if (grid && grid.querySelectorAll('[id^="saved-card-"]').length === 0) {
 grid.style.display = 'none';
 const emptyState = document.getElementById('saved-empty-state');
 if (emptyState) emptyState.style.display = 'block';
 }
 })
 .catch(err => console.error('Error removing bookmark:', err));
}

function toggleGearMenu(btn, e) {
  if (e) e.stopPropagation();
  const menu = btn.nextElementSibling;
  const wasOpen = menu.classList.contains('open');
  document.querySelectorAll('.gear-dropdown-menu.open').forEach(m => m.classList.remove('open'));
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
document.addEventListener('click', function () {
  document.querySelectorAll('.gear-dropdown-menu.open').forEach(m => m.classList.remove('open'));
});
</script>
@if($companies->count() > 0 && isset($ownerAnalytics))
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const canvas = document.getElementById('ownerAnalyticsChart');
  if (!canvas || typeof Chart === 'undefined') return;
  const isDark = document.documentElement.classList.contains('dark');
  const gridColor = isDark ? 'rgba(148,163,184,0.12)' : 'rgba(15,23,42,0.06)';
  const tickColor = isDark ? '#94a3b8' : '#64748b';

  new Chart(canvas, {
    type: 'line',
    data: {
      labels: @json($ownerAnalytics['series']['labels'] ?? []),
      datasets: [
        {
          label: 'بازدید کل',
          data: @json($ownerAnalytics['series']['total'] ?? []),
          borderColor: '#0284c7',
          backgroundColor: 'rgba(2,132,199,0.12)',
          fill: true,
          tension: 0.35,
          borderWidth: 2.5,
          pointRadius: 2
        },
        {
          label: 'بازدید یکتا',
          data: @json($ownerAnalytics['series']['unique'] ?? []),
          borderColor: '#10b981',
          backgroundColor: 'rgba(16,185,129,0.08)',
          fill: true,
          tension: 0.35,
          borderWidth: 2,
          pointRadius: 2
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: { mode: 'index', intersect: false },
      plugins: {
        legend: { labels: { color: tickColor, font: { family: 'Vazirmatn, sans-serif' } } }
      },
      scales: {
        x: { grid: { display: false }, ticks: { color: tickColor, maxTicksLimit: 10 } },
        y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: tickColor, precision: 0 } }
      }
    }
  });
});
</script>
@endif
<script>
function syncUpgradePlanOptions() {
  const typeEl = document.getElementById('upgradeRequestType');
  const planEl = document.getElementById('upgradePlanSelect');
  if (!typeEl || !planEl) return;
  if (typeEl.value === 'featured') {
    planEl.innerHTML = '<option value="gold">ویژه طلایی (اولویت ۱)</option><option value="silver">ویژه نقره‌ای (اولویت ۲)</option><option value="bronze">ویژه برنزی (اولویت ۳)</option>';
  } else {
    planEl.innerHTML = '<option value="12m">اشتراک ۱ ساله</option><option value="6m">اشتراک ۶ ماهه</option><option value="3m">اشتراک ۳ ماهه</option>';
  }
}
</script>
@endsection
