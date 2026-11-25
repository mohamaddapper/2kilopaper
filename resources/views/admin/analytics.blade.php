@extends('admin.layout', ['title' => 'آمار و تحلیل بازدیدها'])

@section('content')
<div>
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px">
    <div>
      <h1 style="margin:0 0 6px;font-size:22px;color:#0f172a">{!! svg_icon('eye', 18) !!} آمار و تحلیل بازدیدهای سایت</h1>
      <p style="margin:0;color:#64748b;font-size:13.5px">گزارش بازدید کل و یکتا، منابع ورودی، تعامل کاربران و پربازدیدترین بخش‌ها (بدون احتساب ربات‌ها)</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn small ghost">بازگشت به پیشخوان</a>
  </div>

  <!-- کارت‌های خلاصه بازدید -->
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px;margin-bottom:24px">
    <div class="stat-card" style="border-right:4px solid #0284c7">
      <div class="stat-icon" style="background:#e0f2fe;color:#0284c7">{!! svg_icon('eye', 16) !!}</div>
      <div>
        <span style="font-size:12px;color:#64748b;display:block">بازدید امروز</span>
        <b style="font-size:22px;color:#0f172a">{{ number_format($kpi['today_total']) }}</b>
        <span style="font-size:11.5px;color:#0284c7;display:block">{{ number_format($kpi['today_unique']) }} بازدید یکتا</span>
      </div>
    </div>

    <div class="stat-card" style="border-right:4px solid #6366f1">
      <div class="stat-icon" style="background:#e0e7ff;color:#4338ca">{!! svg_icon('clock', 16) !!}</div>
      <div>
        <span style="font-size:12px;color:#64748b;display:block">بازدید دیروز</span>
        <b style="font-size:22px;color:#0f172a">{{ number_format($kpi['yesterday_total']) }}</b>
        <span style="font-size:11.5px;color:#64748b;display:block">{{ number_format($kpi['yesterday_unique']) }} بازدید یکتا</span>
      </div>
    </div>

    <div class="stat-card" style="border-right:4px solid #10b981">
      <div class="stat-icon" style="background:#d1fae5;color:#047857">{!! svg_icon('bar-chart', 16) !!}</div>
      <div>
        <span style="font-size:12px;color:#64748b;display:block">۷ روز اخیر</span>
        <b style="font-size:22px;color:#047857">{{ number_format($kpi['week_total']) }}</b>
        <span style="font-size:11.5px;color:{{ $kpi['week_growth'] >= 0 ? '#16a34a' : '#dc2626' }};display:block;font-weight:700">
          {{ $kpi['week_growth'] >= 0 ? '+' : '' }}{{ $kpi['week_growth'] }}٪ نسبت به هفته قبل
        </span>
      </div>
    </div>

    <div class="stat-card" style="border-right:4px solid #f59e0b">
      <div class="stat-icon" style="background:#fef3c7;color:#b45309">{!! svg_icon('star-filled', 16) !!}</div>
      <div>
        <span style="font-size:12px;color:#64748b;display:block">۳۰ روز اخیر</span>
        <b style="font-size:22px;color:#b45309">{{ number_format($kpi['month_total']) }}</b>
        <span style="font-size:11.5px;color:#64748b;display:block">{{ number_format($kpi['month_unique']) }} بازدید یکتا</span>
      </div>
    </div>
  </div>

  <!-- نمودار روند ۳۰ روزه + منابع ورودی -->
  <div style="display:grid;grid-template-columns:2fr 1fr;gap:16px;margin-bottom:24px" class="analytics-top-grid">
    <div class="admin-table-card" style="padding:18px 20px">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px">
        <h3 style="margin:0;font-size:15.5px;color:#0f172a">روند بازدید ۳۰ روز گذشته</h3>
        <span style="font-size:12px;color:#64748b">بازدید کل در برابر یکتا</span>
      </div>
      <div style="position:relative;height:270px">
        <canvas id="adminTrafficChart"></canvas>
      </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:16px">
      <div class="admin-table-card" style="padding:18px 20px">
        <h3 style="margin:0 0 12px;font-size:15px;color:#0f172a">منابع ورودی (۳۰ روز)</h3>
        @php
          $totalSrc = max(1, array_sum($sources));
          $srcList = [
            ['label' => 'جستجوی گوگل و موتورها', 'val' => $sources['google'], 'color' => '#10b981'],
            ['label' => 'جستجوی داخلی سایت', 'val' => $sources['search'], 'color' => '#0284c7'],
            ['label' => 'تورق داخلی صفحات', 'val' => $sources['internal'], 'color' => '#6366f1'],
            ['label' => 'ورود مستقیم / سایر', 'val' => $sources['direct'], 'color' => '#f59e0b'],
          ];
        @endphp
        <div style="display:flex;flex-direction:column;gap:10px">
          @foreach($srcList as $s)
            @php $pct = round(($s['val'] / $totalSrc) * 100); @endphp
            <div>
              <div style="display:flex;justify-content:space-between;font-size:12.5px;margin-bottom:4px">
                <span style="color:#334155">{{ $s['label'] }}</span>
                <b style="color:#0f172a">{{ number_format($s['val']) }} ({{ $pct }}٪)</b>
              </div>
              <div style="height:6px;background:#e2e8f0;border-radius:99px;overflow:hidden">
                <div style="height:100%;width:{{ $pct }}%;background:{{ $s['color'] }};border-radius:99px"></div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <div class="admin-table-card" style="padding:18px 20px">
        <h3 style="margin:0 0 12px;font-size:15px;color:#0f172a">تعامل کاربران با شرکت‌ها (۳۰ روز)</h3>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;font-size:12.5px">
          <div style="padding:10px;border-radius:8px;background:#f8fafc;border:1px solid #e2e8f0">
            <span style="color:#64748b;display:block">کلیک تماس تلفنی</span>
            <b style="font-size:17px;color:#0284c7">{{ number_format($leadTotals['phone']) }}</b>
          </div>
          <div style="padding:10px;border-radius:8px;background:#f8fafc;border:1px solid #e2e8f0">
            <span style="color:#64748b;display:block">کلیک واتساپ</span>
            <b style="font-size:17px;color:#16a34a">{{ number_format($leadTotals['whatsapp']) }}</b>
          </div>
          <div style="padding:10px;border-radius:8px;background:#f8fafc;border:1px solid #e2e8f0">
            <span style="color:#64748b;display:block">بازدید وب‌سایت</span>
            <b style="font-size:17px;color:#6366f1">{{ number_format($leadTotals['website']) }}</b>
          </div>
          <div style="padding:10px;border-radius:8px;background:#f8fafc;border:1px solid #e2e8f0">
            <span style="color:#64748b;display:block">ذخیره‌سازی</span>
            <b style="font-size:17px;color:#d97706">{{ number_format($leadTotals['bookmark']) }}</b>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- پربازدیدترین شرکت‌ها -->
  <div class="admin-table-card" style="margin-bottom:24px">
    <div style="padding:14px 18px;border-bottom:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center">
      <h3 style="margin:0;font-size:15.5px;color:#0f172a">{!! svg_icon('building', 15) !!} ۱۰ شرکت پربازدید (۳۰ روز اخیر)</h3>
    </div>
    <table class="admin-table">
      <thead>
        <tr>
          <th>#</th>
          <th>نام شرکت</th>
          <th>دسته‌بندی</th>
          <th style="text-align:center">بازدید ۳۰ روز</th>
          <th style="text-align:center">بازدید یکتا</th>
          <th style="text-align:center">کلیک ارتباطی</th>
          <th style="text-align:center">کل بازدید</th>
        </tr>
      </thead>
      <tbody>
        @forelse($topCompanies as $idx => $c)
          <tr>
            <td>{{ $idx + 1 }}</td>
            <td><a href="{{ $c->url }}" target="_blank" style="font-weight:700;color:#0f172a;text-decoration:none">{{ $c->name }}</a></td>
            <td style="color:#64748b;font-size:12.5px">{{ $c->category->name ?? '—' }}</td>
            <td style="text-align:center;font-weight:700;color:#0284c7">{{ number_format($c->views_30d) }}</td>
            <td style="text-align:center">{{ number_format($c->unique_30d) }}</td>
            <td style="text-align:center"><span class="badge ok">{{ number_format($c->leads_30d) }}</span></td>
            <td style="text-align:center;color:#64748b">{{ number_format($c->views_count ?? 0) }}</td>
          </tr>
        @empty
          <tr><td colspan="7" style="text-align:center;padding:24px;color:#64748b">هنوز داده‌ای ثبت نشده است.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- دسته‌ها، استان‌ها و آگهی‌های پربازدید -->
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px">
    <div class="admin-table-card">
      <div style="padding:14px 18px;border-bottom:1px solid #e2e8f0">
        <h3 style="margin:0;font-size:15px;color:#0f172a">۵ دسته‌بندی پربازدید</h3>
      </div>
      <table class="admin-table">
        <tbody>
          @forelse($topCategories as $cat)
            <tr>
              <td><a href="{{ $cat->url }}" target="_blank" style="color:#0f172a;text-decoration:none;font-weight:600">{{ $cat->name }}</a></td>
              <td style="text-align:left;font-weight:700;color:#0284c7">{{ number_format($cat->views_30d) }}</td>
            </tr>
          @empty
            <tr><td colspan="2" style="text-align:center;padding:18px;color:#64748b">داده ۳۰ روزه ثبت نشده است.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="admin-table-card">
      <div style="padding:14px 18px;border-bottom:1px solid #e2e8f0">
        <h3 style="margin:0;font-size:15px;color:#0f172a">۵ استان پربازدید</h3>
      </div>
      <table class="admin-table">
        <tbody>
          @forelse($topProvinces as $prov)
            <tr>
              <td><a href="{{ $prov->url }}" target="_blank" style="color:#0f172a;text-decoration:none;font-weight:600">{{ $prov->name }}</a></td>
              <td style="text-align:left;font-weight:700;color:#10b981">{{ number_format($prov->views_30d) }}</td>
            </tr>
          @empty
            <tr><td colspan="2" style="text-align:center;padding:18px;color:#64748b">داده ۳۰ روزه ثبت نشده است.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="admin-table-card">
      <div style="padding:14px 18px;border-bottom:1px solid #e2e8f0">
        <h3 style="margin:0;font-size:15px;color:#0f172a">۵ آگهی استخدام پربازدید</h3>
      </div>
      <table class="admin-table">
        <tbody>
          @forelse($topJobs as $job)
            <tr>
              <td>
                <a href="{{ route('jobs.show', $job) }}" target="_blank" style="color:#0f172a;text-decoration:none;font-weight:600;display:block">{{ $job->title }}</a>
                <span style="font-size:11.5px;color:#64748b">{{ $job->company->name ?? '' }}</span>
              </td>
              <td style="text-align:left;font-weight:700;color:#6366f1">{{ number_format($job->views_30d) }}</td>
            </tr>
          @empty
            <tr><td colspan="2" style="text-align:center;padding:18px;color:#64748b">آگهی ثبت نشده است.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<style>
@media (max-width: 960px) {
  .analytics-top-grid { grid-template-columns: 1fr !important; }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const canvas = document.getElementById('adminTrafficChart');
  if (!canvas || typeof Chart === 'undefined') return;
  const isDark = document.documentElement.classList.contains('dark');
  const gridColor = isDark ? 'rgba(148,163,184,0.12)' : 'rgba(15,23,42,0.06)';
  const tickColor = isDark ? '#94a3b8' : '#64748b';

  new Chart(canvas, {
    type: 'line',
    data: {
      labels: @json($series['labels']),
      datasets: [
        {
          label: 'بازدید کل',
          data: @json($series['total']),
          borderColor: '#0284c7',
          backgroundColor: 'rgba(2,132,199,0.12)',
          fill: true,
          tension: 0.35,
          borderWidth: 2.5,
          pointRadius: 2.5
        },
        {
          label: 'بازدید یکتا',
          data: @json($series['unique']),
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
@endsection
