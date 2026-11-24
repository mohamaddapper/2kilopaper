@php
  $h1Title = 'کارخانجات و شرکت‌های صنعت کاغذ، کارتن و چاپ در استان ' . $province->name;
  $seoTitle = "تولیدکنندگان و فروشندگان کاغذ و کارتن در {$province->name} | ۲ کیلو کاغذ";
  $metaDesc = "فهرست کامل {$companies->total()} کارخانه کارتن‌سازی، فروشنده کاغذ، واردکننده و چاپخانه فعال در استان {$province->name} به همراه آدرس و تلفن تماس.";
@endphp

@extends('layouts.app', [
  'title' => $seoTitle,
  'description' => $metaDesc,
  'canonical' => $province->url,
])

@push('head')
<script type="application/ld+json">{!! json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'BreadcrumbList',
  'itemListElement' => [
    ['@type' => 'ListItem', 'position' => 1, 'name' => 'خانه', 'item' => url('/')],
    ['@type' => 'ListItem', 'position' => 2, 'name' => 'استان‌ها', 'item' => route('locations.index')],
    ['@type' => 'ListItem', 'position' => 3, 'name' => $province->name, 'item' => $province->url],
  ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')
<div style="margin:20px 0 60px;width:100%">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" style="font-size:12.5px;color:#64748b;margin-bottom:14px;display:flex;align-items:center;gap:6px;flex-wrap:wrap">
    <a href="{{ route('home') }}" style="color:#64748b;text-decoration:none">خانه</a>
    <span>›</span>
    <a href="{{ route('locations.index') }}" style="color:#64748b;text-decoration:none">موقعیت مکانی (استان‌ها)</a>
    <span>›</span>
    <span style="color:#0f172a;font-weight:700">{{ $province->name }}</span>
  </nav>

  <!-- Header -->
  <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:22px 24px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px">
    <div>
      <h1 style="margin:0 0 6px;font-size:22px;font-weight:900;color:#0f172a">
        📍 {{ $h1Title }}
      </h1>
      <p style="margin:0;font-size:13.5px;color:#64748b">
        نمایش <strong>{{ number_format($companies->total()) }}</strong> واحد صنعتی و بازرگانی فعال در استان {{ $province->name }}
      </p>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap">
      <a href="{{ route('locations.index') }}" class="btn ghost small">مشاهده همه استان‌ها</a>
      <a href="{{ route('companies.create') }}" class="btn small">ثبت رایگان شرکت در {{ $province->name }}</a>
    </div>
  </div>

  <!-- دسته‌بندی‌های برتر در این استان -->
  @if($topCategories->isNotEmpty())
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:14px 18px;margin-bottom:22px">
      <div style="font-size:12.5px;font-weight:800;color:#475569;margin-bottom:10px">
        🏷️ دسته‌بندی‌های تخصصی فعال در استان {{ $province->name }}:
      </div>
      <div style="display:flex;gap:8px;flex-wrap:wrap">
        @foreach($topCategories as $cat)
          @php
            $comboUrl = route('categories.province', [
              'role' => $cat->role_slug,
              'category' => slugify($cat->name),
              'province' => slugify($province->name),
            ]);
          @endphp
          <a href="{{ $comboUrl }}" style="display:inline-flex;align-items:center;gap:6px;padding:5px 12px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:20px;font-size:12.5px;font-weight:600;color:#334155;text-decoration:none">
            <span>{{ $cat->role_icon }}</span>
            <span>{{ $cat->smart_h1 }}</span>
            <span style="font-size:11px;color:#64748b">({{ $topCategoryCounts[$cat->id] ?? 0 }})</span>
          </a>
        @endforeach
      </div>
    </div>
  @endif

  <!-- لیست شرکت‌ها (۶ ستونه با اسکرول بی‌نهایت) -->
  @if($companies->count())
    <div id="companies-grid" class="directory-grid-6col">
      @foreach($companies as $c)
        @include('partials.card', ['company' => $c])
      @endforeach
    </div>

    <div id="infinite-scroll-trigger" style="text-align:center;padding:28px 0 10px;min-height:60px">
      <div id="infinite-loader" style="display:none;align-items:center;justify-content:center;gap:10px;color:var(--primary);font-weight:700;font-size:14px">
        <span style="display:inline-block;width:22px;height:22px;border:3px solid #e2e8f0;border-top-color:var(--primary);border-radius:50%;animation:spin 0.7s linear infinite"></span>
        <span>در حال بارگذاری ۱۸ شرکت بعدی...</span>
      </div>
      <div id="infinite-end" style="{{ $companies->hasMorePages() ? 'display:none;' : 'display:block;' }}color:#94a3b8;font-size:13px;padding:8px 0">
        پایان فهرست شرکت‌های استان {{ $province->name }}
      </div>
    </div>
  @else
    <div class="card" style="padding:48px 24px;text-align:center;color:var(--muted);border-radius:16px">
      <div style="font-size:42px;margin-bottom:12px">📍</div>
      <h3 style="margin:0 0 8px;font-size:18px;color:var(--text)">شرکتی در استان {{ $province->name }} ثبت نشده است</h3>
      <p style="margin:0 0 18px;font-size:14px">اولین شرکت فعال این استان باشید و اطلاعات خود را رایگان ثبت کنید.</p>
      <a href="{{ route('companies.create') }}" class="btn small">ثبت رایگان شرکت</a>
    </div>
  @endif

  <!-- سایر استان‌ها -->
  @if($otherProvinces->isNotEmpty())
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:16px 20px;margin-top:28px">
      <div style="font-size:13px;font-weight:800;color:#0f172a;margin-bottom:10px">سایر استان‌های فعال:</div>
      <div style="display:flex;gap:8px;flex-wrap:wrap">
        @foreach($otherProvinces as $op)
          <a href="{{ $op->url }}" style="font-size:12.5px;color:#475569;background:#f8fafc;border:1px solid #e2e8f0;border-radius:18px;padding:4px 12px;text-decoration:none">
            {{ $op->name }} ({{ $op->companies_count }})
          </a>
        @endforeach
      </div>
    </div>
  @endif
</div>

<style>
@keyframes spin { to { transform: rotate(360deg); } }
</style>

@if($companies->hasMorePages())
<script>
document.addEventListener('DOMContentLoaded', function() {
  const grid = document.getElementById('companies-grid');
  const trigger = document.getElementById('infinite-scroll-trigger');
  const loader = document.getElementById('infinite-loader');
  const endMsg = document.getElementById('infinite-end');
  if (!grid || !trigger) return;

  let nextPageUrl = @json($companies->nextPageUrl());
  let isLoading = false;

  async function loadNextBatch() {
    if (!nextPageUrl || isLoading) return;
    isLoading = true;
    loader.style.display = 'inline-flex';
    try {
      const url = new URL(nextPageUrl, window.location.origin);
      url.searchParams.set('ajax', '1');
      const res = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } });
      if (!res.ok) throw new Error('Network error');
      const data = await res.json();
      if (data.html) grid.insertAdjacentHTML('beforeend', data.html);
      nextPageUrl = data.has_more ? data.next_page_url : null;
      if (!nextPageUrl) endMsg.style.display = 'block';
    } catch (e) {
      console.error(e);
    } finally {
      loader.style.display = 'none';
      isLoading = false;
    }
  }

  const observer = new IntersectionObserver((entries) => {
    if (entries[0].isIntersecting && nextPageUrl && !isLoading) loadNextBatch();
  }, { rootMargin: '250px' });
  observer.observe(trigger);
});
</script>
@endif
@endsection
