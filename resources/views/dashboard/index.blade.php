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
          <span>👑 پنل مدیریت کل سایت</span>
        </a>
        <a href="{{ route('admin.companies') }}" class="dashboard-menu-link" style="background:#f0fdf4;color:#15803d;font-weight:700">
          <span>🏢 مدیریت و ویرایش ۹۱۹ شرکت</span>
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
                        <a class="btn ghost small" href="{{ $c->url }}" target="_blank" style="padding:3px 12px;font-size:12px">مشاهده</a>
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
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;flex-wrap:wrap;gap:8px">
        <h2 style="margin:0;font-size:18px">شرکت‌های من</h2>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
          @if(auth()->user()->isAdmin())
            <a class="btn" href="{{ route('admin.companies') }}" style="background:#0284c7;font-weight:700">🏢 مدیریت و ویرایش تمام شرکت‌ها</a>
          @endif
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
                      <a class="btn ghost small" href="{{ $c->url }}" target="_blank">مشاهده</a>
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
                      👥 {{ $job->applications->count() }} رزومه
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
            <span style="font-size:36px;display:block;margin-bottom:8px">📢</span>
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
                    <span style="display:block;font-size:11.5px;color:var(--muted)">{{ $app->created_at->diffForHumans() }} · 👁️ {{ number_format($app->views_count ?? 0) }} بازدید</span>
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
            <span style="font-size:36px;display:block;margin-bottom:8px">📭</span>
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
          <h2 style="margin:0 0 4px;font-size:18px">اشتراک و ارتقای خدمات</h2>
          <p style="margin:0;font-size:13px;color:var(--muted)">مدیریت پلن‌ها، افزایش شانس دیده‌شدن و استفاده از تمام امکانات دایرکتوری</p>
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

      <!-- وضعیت اشتراک شرکت‌های کاربر -->
      @if($companies->count() > 0)
        <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:24px">
          @foreach($companies as $c)
            <div class="card" style="padding:16px 20px;background:#fff;border-radius:10px;border:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">
              <div>
                <b style="font-size:15px;color:var(--text)">{{ $c->name }}</b>
                <div style="font-size:12.5px;color:var(--muted);margin-top:4px">
                  وضعیت فعلی:
                  @if($c->tier === 'gold')
                    <span class="badge gold" style="font-size:11px">★ اشتراک طلایی (۱ ساله)</span>
                  @elseif($c->tier === 'silver')
                    <span class="badge silver" style="font-size:11px">★ اشتراک نقره‌ای (۶ ماهه)</span>
                  @elseif($c->tier === 'bronze')
                    <span class="badge bronze" style="font-size:11px">★ اشتراک برنزی (۳ ماهه)</span>
                  @else
                    <span class="badge" style="background:#f1f5f9;color:#64748b;font-size:11px">عادی (رایگان)</span>
                  @endif

                  @if($c->tier_expires_at)
                    · <span style="color:#0284c7">اعتبار تا {{ $c->tier_expires_at->format('Y/m/d') }} ({{ $c->tier_expires_at->diffForHumans() }})</span>
                  @endif
                </div>
              </div>

              <div>
                <a href="#plans_grid" class="btn small" style="background:var(--primary);font-size:12px">
                  {{ $c->tier ? 'تمدید یا تغییر اشتراک' : 'ارتقا به اشتراک ویژه' }}
                </a>
              </div>
            </div>
          @endforeach
        </div>
      @endif

      <!-- کارت‌های ۳ گانه اشتراک -->
      <h3 style="margin:0 0 14px;font-size:16px;color:var(--text)" id="plans_grid">پلن‌های اشتراک ۲ کیلو کاغذ</h3>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px;margin-bottom:28px">
        
        <div class="card" style="padding:20px;background:#fff;border-radius:12px;border:1px solid var(--border);display:flex;flex-direction:column;justify-content:space-between">
          <div>
            <span style="font-size:12px;color:var(--muted);font-weight:600">دوره کوتاه‌مدت</span>
            <h4 style="margin:4px 0 10px;font-size:16px">اشتراک ۳ ماهه</h4>
            <div style="font-size:20px;font-weight:800;color:var(--primary);margin-bottom:12px">
              ۳/۰۰۰/۰۰۰ تومان
            </div>
            <ul style="list-style:none;padding:0;margin:0;font-size:12.5px;line-height:2;color:#334155">
              <li>✓ درج تصاویر و گالری محصولات</li>
              <li>✓ درج آگهی استخدام نامحدود</li>
              <li>✓ افزودن کاتالوگ محصولات (PDF)</li>
              <li>✓ حذف تبلیغات رقبا از صفحه شما</li>
              <li>✓ جایگاه بالاتر از شرکت‌های عادی</li>
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
            <div style="font-size:20px;font-weight:800;color:var(--primary);margin-bottom:12px">
              ۴/۰۰۰/۰۰۰ تومان
            </div>
            <ul style="list-style:none;padding:0;margin:0;font-size:12.5px;line-height:2;color:#334155">
              <li>✓ درج تصاویر و گالری محصولات</li>
              <li>✓ درج آگهی استخدام نامحدود</li>
              <li>✓ افزودن کاتالوگ محصولات (PDF)</li>
              <li>✓ حذف تبلیغات رقبا از صفحه شما</li>
              <li>✓ جایگاه بالاتر از شرکت‌های عادی</li>
            </ul>
          </div>
          <div style="margin-top:16px">
            <a href="tel:02128421075" class="btn ghost small" style="width:100%;text-align:center;box-sizing:border-box">سفارش: ۰۲۱۲۸۴۲۱۰۷۵</a>
          </div>
        </div>

        <div class="card" style="padding:20px;background:#fefce8;border-radius:12px;border:2px solid #eab308;display:flex;flex-direction:column;justify-content:space-between">
          <div>
            <span style="font-size:12px;color:#854d0e;font-weight:700">★ کامل‌ترین و به‌صرفه‌ترین</span>
            <h4 style="margin:4px 0 10px;font-size:16px;color:#713f12">اشتراک ۱ ساله (طلایی)</h4>
            <div style="font-size:20px;font-weight:800;color:#854d0e;margin-bottom:12px">
              ۵/۰۰۰/۰۰۰ تومان
            </div>
            <ul style="list-style:none;padding:0;margin:0;font-size:12.5px;line-height:2;color:#713f12">
              <li>✓ تمام امکانات گالری، کاتالوگ و استخدام</li>
              <li>✓ درج ویدیوی معرفی کالا و خدمات</li>
              <li>✓ اطلاع‌رسانی پیامکی اختصاصی به مدیر</li>
              <li>✓ حذف کامل تبلیغات رقبا</li>
              <li>★ بالاترین اولویت در نتایج و نشان طلایی</li>
            </ul>
          </div>
          <div style="margin-top:16px">
            <a href="tel:02128421075" class="btn small" style="width:100%;text-align:center;background:#eab308;color:#000;font-weight:700;box-sizing:border-box">سفارش اشتراک ۱ ساله</a>
          </div>
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
          <h2 style="margin:0 0 6px;font-size:18px;color:#1e293b">⭐ شرکت‌های ذخیره‌شده و برگزیده</h2>
          <p style="margin:0;font-size:13px;color:var(--muted)">شرکت‌ها و کارخانجاتی که برای دسترسی سریع به میز کار خود اضافه کرده‌اید</p>
        </div>
        <a href="{{ route('directory') }}" class="btn ghost small" style="font-size:13px;padding:6px 14px">
          🔍 جستجو در دایرکتوری شرکت‌ها
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
                      <span>📞</span>
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
            <div style="font-size:42px;margin-bottom:10px">📑</div>
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
            <div style="font-size:42px;margin-bottom:10px">📑</div>
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
</script>
@endsection
