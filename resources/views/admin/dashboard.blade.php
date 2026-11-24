@extends('admin.layout', ['title' => 'پیشخوان مدیریت'])

@section('content')
<div>

  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px">
    <div>
      <h1 style="margin:0 0 6px;font-size:22px;color:#0f172a">📊 پیشخوان مدیریت ارشد ۲ کیلو کاغذ</h1>
      <p style="margin:0;color:#64748b;font-size:13.5px">خلاصه وضعیت دایرکتوری شرکت‌ها، آگهی‌های استخدامی و رزومه‌های صنعتی</p>
    </div>
    <div style="display:flex;gap:8px">
      <a href="{{ route('admin.companies', ['status' => 'pending']) }}" class="btn small" style="background:#f59e0b;color:#000;font-weight:700">
        ⏳ بررسی شرکت‌های جدید ({{ $stats['companies_pending'] }})
      </a>
      <a href="{{ route('admin.jobs') }}" class="btn small ghost">
        مدیریت آگهی‌ها
      </a>
    </div>
  </div>

  <!-- کارت‌های آمار کلیدی -->
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:14px;margin-bottom:24px">
    
    <div class="stat-card" style="border-right:4px solid #f59e0b">
      <div class="stat-icon" style="background:#fef3c7;color:#b45309">⏳</div>
      <div>
        <span style="font-size:12px;color:#64748b;display:block">در انتظار بررسی</span>
        <b style="font-size:22px;color:#b45309">{{ number_format($stats['companies_pending']) }}</b>
      </div>
    </div>

    <div class="stat-card" style="border-right:4px solid #0284c7">
      <div class="stat-icon" style="background:#e0f2fe;color:#0284c7">🏢</div>
      <div>
        <span style="font-size:12px;color:#64748b;display:block">کل شرکت‌ها</span>
        <b style="font-size:22px;color:#0f172a">{{ number_format($stats['companies_total']) }}</b>
        <span style="font-size:11px;color:#16a34a;display:block">({{ $stats['companies_approved'] }} فعال)</span>
      </div>
    </div>

    <div class="stat-card" style="border-right:4px solid #eab308">
      <div class="stat-icon" style="background:#fef9c3;color:#854d0e">★</div>
      <div>
        <span style="font-size:12px;color:#64748b;display:block">اشتراک‌های ویژه</span>
        <b style="font-size:22px;color:#854d0e">{{ number_format($stats['companies_gold'] + $stats['companies_silver'] + $stats['companies_bronze']) }}</b>
        <span style="font-size:11px;color:#64748b;display:block">{{ $stats['companies_gold'] }} طلایی · {{ $stats['companies_silver'] }} نقره‌ای</span>
      </div>
    </div>

    <div class="stat-card" style="border-right:4px solid #10b981">
      <div class="stat-icon" style="background:#d1fae5;color:#047857">💼</div>
      <div>
        <span style="font-size:12px;color:#64748b;display:block">آگهی‌های استخدامی</span>
        <b style="font-size:22px;color:#047857">{{ number_format($stats['jobs_active']) }}</b>
        <span style="font-size:11px;color:#dc2626;display:block">{{ $stats['jobs_immediate'] }} آگهی فوری</span>
      </div>
    </div>

    <div class="stat-card" style="border-right:4px solid #6366f1">
      <div class="stat-icon" style="background:#e0e7ff;color:#4338ca">👥</div>
      <div>
        <span style="font-size:12px;color:#64748b;display:block">رزومه‌ها و متقاضیان</span>
        <b style="font-size:22px;color:#4338ca">{{ number_format($stats['applications_total']) }}</b>
        <span style="font-size:11px;color:#64748b;display:block">{{ $stats['applications_new'] }} رزومه جدید</span>
      </div>
    </div>

    <div class="stat-card" style="border-right:4px solid #8b5cf6">
      <div class="stat-icon" style="background:#ede9fe;color:#6d28d9">👤</div>
      <div>
        <span style="font-size:12px;color:#64748b;display:block">کاربران عضو سامانه</span>
        <b style="font-size:22px;color:#6d28d9">{{ number_format($stats['users_total']) }}</b>
      </div>
    </div>

  </div>

  <!-- شرکت‌های در انتظار تأیید -->
  <div class="admin-table-card">
    <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center">
      <div style="display:flex;align-items:center;gap:8px">
        <span style="font-size:18px">⏳</span>
        <h3 style="margin:0;font-size:16px;color:#0f172a">شرکت‌های در انتظار بررسی و تأیید</h3>
        <span class="badge" style="background:#fef3c7;color:#b45309;font-size:11.5px">{{ $pendingCompanies->count() }} مورد</span>
      </div>
      <a href="{{ route('admin.companies', ['status' => 'pending']) }}" style="font-size:12.5px;color:var(--admin-primary);text-decoration:none">
        مشاهده تمام موارد در انتظار ←
      </a>
    </div>

    @if($pendingCompanies->count() > 0)
      <table class="admin-table">
        <thead>
          <tr>
            <th>نام شرکت / کارخانه</th>
            <th>دسته‌بندی تخصصی</th>
            <th>موقعیت مکانی</th>
            <th>کاربر ثبت‌کننده</th>
            <th>تاریخ ثبت</th>
            <th style="text-align:left">عملیات ادمین</th>
          </tr>
        </thead>
        <tbody>
          @foreach($pendingCompanies as $c)
            <tr>
              <td>
                <b>{{ $c->name }}</b>
                @if($c->phone)
                  <span style="display:block;font-size:11.5px;color:#64748b;direction:ltr;text-align:right">{{ $c->phone }}</span>
                @endif
              </td>
              <td>
                <span class="badge" style="background:#f1f5f9;color:#334155;font-size:12px">
                  {{ $c->category->name ?? '—' }}
                </span>
              </td>
              <td style="color:#64748b">
                {{ $c->city->name ?? '—' }}
              </td>
              <td>
                {{ $c->user->phone ?? '—' }}
              </td>
              <td style="color:#64748b;font-size:12.5px">
                {{ $c->created_at->diffForHumans() }}
              </td>
              <td style="text-align:left">
                <div style="display:inline-flex;gap:6px">
                  <form method="post" action="{{ route('admin.companies.approve', $c) }}" style="margin:0">
                    @csrf
                    <button type="submit" class="btn-action btn-approve" onclick="return confirm('آیا از تأیید و انتشار این شرکت اطمینان دارید؟')">
                      ✓ تأیید و انتشار
                    </button>
                  </form>
                  <form method="post" action="{{ route('admin.companies.reject', $c) }}" style="margin:0">
                    @csrf
                    <button type="submit" class="btn-action btn-reject" onclick="return confirm('شرکت رد/معلق شود؟')">
                      ✕ رد
                    </button>
                  </form>
                  <a href="{{ route('company', $c) }}" target="_blank" class="btn-action btn-view">
                    مشاهده ↗
                  </a>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @else
      <div style="padding:32px;text-align:center;color:#64748b">
        <span style="font-size:32px;display:block;margin-bottom:8px">🎉</span>
        <b>عالیه! در حال حاضر هیچ شرکتی در صف انتظار بررسی وجود ندارد.</b>
      </div>
    @endif
  </div>

  <!-- دو ستون: آخرین آگهی‌های شغلی و آخرین رزومه‌ها -->
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:20px">
    
    <!-- آخرین آگهی‌های استخدام -->
    <div class="admin-table-card">
      <div style="padding:14px 18px;border-bottom:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center">
        <h3 style="margin:0;font-size:15px;color:#0f172a">💼 آخرین فرصت‌های شغلی ثبت‌شده</h3>
        <a href="{{ route('admin.jobs') }}" style="font-size:12px;color:var(--admin-primary);text-decoration:none">مشاهده همه</a>
      </div>
      <table class="admin-table">
        <tbody>
          @forelse($recentJobs as $j)
            <tr>
              <td>
                <a href="{{ route('jobs.show', $j) }}" target="_blank" style="color:#0f172a;font-weight:700;text-decoration:none">
                  {{ $j->title }}
                </a>
                <span style="display:block;font-size:11.5px;color:#64748b">
                  {{ $j->company->name ?? 'مستقل' }} · {{ $j->city->name ?? '' }}
                </span>
              </td>
              <td style="text-align:left">
                @if($j->is_immediate)
                  <span class="badge bad" style="font-size:10.5px">فوری</span>
                @endif
                <span class="badge {{ $j->status === 'active' ? 'ok' : '' }}" style="font-size:10.5px">
                  {{ $j->status === 'active' ? 'فعال' : 'متوقف' }}
                </span>
              </td>
            </tr>
          @empty
            <tr><td colspan="2" style="text-align:center;color:#64748b">هنوز آگهی ثبت نشده است.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- آخرین متقاضیان و رزومه‌ها -->
    <div class="admin-table-card">
      <div style="padding:14px 18px;border-bottom:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center">
        <h3 style="margin:0;font-size:15px;color:#0f172a">👥 آخرین متقاضیان و رزومه‌های کارجو</h3>
        <a href="{{ route('admin.applications') }}" style="font-size:12px;color:var(--admin-primary);text-decoration:none">مشاهده همه</a>
      </div>
      <table class="admin-table">
        <tbody>
          @forelse($recentApplications as $app)
            <tr>
              <td>
                <b>{{ $app->full_name }}</b>
                <span style="display:block;font-size:11.5px;color:#64748b">
                  برای شغل: {{ $app->jobPosting->title ?? '—' }} ({{ $app->jobPosting->company->name ?? '' }})
                </span>
              </td>
              <td style="text-align:left">
                @if($app->resume_file)
                  <a href="{{ asset($app->resume_file) }}" target="_blank" class="btn-action btn-view" style="font-size:11px">
                    دانلود PDF 📥
                  </a>
                @else
                  <span style="font-size:11px;color:#94a3b8">بدون فایل</span>
                @endif
              </td>
            </tr>
          @empty
            <tr><td colspan="2" style="text-align:center;color:#64748b">رزومه‌ای ثبت نشده است.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>

</div>
@endsection
