@php
  $h1Title = $category->smart_h1 . ($province ? ' در ' . $province->name : '');
  $seoTitle = $province
      ? ($category->smart_h1 . ' در ' . $province->name . ' | لیست کارخانه‌ها و تلفن تماس')
      : $category->smart_seo_title;
  $metaDesc = $province
      ? ("فهرست کامل {$category->smart_h1} در استان {$province->name} به همراه آدرس، شماره تماس مستقیم و مشخصات فعالیت در ۲ کیلو کاغذ.")
      : $category->smart_meta_description;
  $canonicalUrl = $province
      ? route('categories.province', ['role' => $category->role_slug, 'category' => slugify($category->name), 'province' => slugify($province->name)])
      : $category->url;

  $breadcrumbs = [
    ['@type' => 'ListItem', 'position' => 1, 'name' => 'خانه', 'item' => url('/')],
    ['@type' => 'ListItem', 'position' => 2, 'name' => 'دسته‌بندی فعالیت‌ها', 'item' => route('categories.index')],
  ];
  $pos = 3;
  if ($category->id !== $pillar->id) {
    $breadcrumbs[] = ['@type' => 'ListItem', 'position' => $pos++, 'name' => $pillar->name, 'item' => $pillar->url];
  }
  $breadcrumbs[] = ['@type' => 'ListItem', 'position' => $pos++, 'name' => $category->name, 'item' => $category->url];
  if ($province) {
    $breadcrumbs[] = ['@type' => 'ListItem', 'position' => $pos++, 'name' => $province->name, 'item' => $canonicalUrl];
  }

  $faqItems = array_values(array_filter((array) ($category->faqs ?? []), fn($f) => !empty($f['q']) && !empty($f['a'])));
@endphp

@extends('layouts.app', [
  'title' => $seoTitle,
  'description' => $metaDesc,
  'canonical' => $canonicalUrl,
])

@push('head')
<script type="application/ld+json">{!! json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'BreadcrumbList',
  'itemListElement' => $breadcrumbs,
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>

<script type="application/ld+json">{!! json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'CollectionPage',
  'name' => $h1Title,
  'description' => $metaDesc,
  'url' => $canonicalUrl,
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>

@if(!empty($faqItems))
<script type="application/ld+json">{!! json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'FAQPage',
  'mainEntity' => array_map(fn($f) => [
    '@type' => 'Question',
    'name' => $f['q'],
    'acceptedAnswer' => [
      '@type' => 'Answer',
      'text' => $f['a'],
    ],
  ], $faqItems),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endif
@endpush

@section('content')
<div style="margin:20px 0 60px;width:100%">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" style="font-size:12.5px;color:#64748b;margin-bottom:14px;display:flex;align-items:center;gap:6px;flex-wrap:wrap">
    <a href="{{ route('home') }}" style="color:#64748b;text-decoration:none">خانه</a>
    <span>›</span>
    <a href="{{ route('categories.index') }}" style="color:#64748b;text-decoration:none">دسته‌بندی فعالیت‌ها</a>
    <span>›</span>
    @if($category->id !== $pillar->id)
      <a href="{{ $pillar->url }}" style="color:#64748b;text-decoration:none">{{ $pillar->name }}</a>
      <span>›</span>
    @endif
    @if($province)
      <a href="{{ $category->url }}" style="color:#64748b;text-decoration:none">{{ $category->name }}</a>
      <span>›</span>
      <span style="color:#0f172a;font-weight:700">{{ $province->name }}</span>
    @else
      <span style="color:#0f172a;font-weight:700">{{ $category->name }}</span>
    @endif
  </nav>

  <!-- Header -->
  <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:22px 24px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px">
    <div>
      <h1 style="margin:0 0 6px;font-size:23px;font-weight:900;color:#0f172a;display:flex;align-items:center;gap:8px;flex-wrap:wrap">
        <span>{{ $category->role_icon }}</span>
        <span>{{ $h1Title }}</span>
      </h1>
      <p style="margin:0;font-size:13.5px;color:#64748b">
        مجموعاً <strong>{{ number_format($companies->total()) }}</strong> شرکت و واحد فعال ثبت‌شده
        @if($province)
          در استان <strong>{{ $province->name }}</strong>
        @endif
      </p>
    </div>

    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
      @if($province)
        <a href="{{ $category->url }}" class="btn ghost small">مشاهده در کل ایران</a>
      @endif
      @if(auth()->check() && auth()->user()->isAdmin())
        <a href="{{ route('admin.categories.edit', $category) }}" target="_blank" class="btn small" style="background:#f59e0b;color:#000;font-weight:700">
          ✏️ ویرایش سئو این دسته
        </a>
      @endif
      <a href="{{ route('companies.create') }}" class="btn small">ثبت رایگان شرکت در این دسته</a>
    </div>
  </div>

  <!-- زیردسته‌ها یا دسته‌های مرتبط -->
  @if($relatedCategories->isNotEmpty())
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:14px 18px;margin-bottom:16px">
      <div style="font-size:12.5px;font-weight:800;color:#475569;margin-bottom:10px">
        {{ $category->children->isNotEmpty() ? 'زیردسته‌های تخصصی:' : 'دسته‌بندی‌های مرتبط:' }}
      </div>
      <div style="display:flex;gap:8px;flex-wrap:wrap">
        @foreach($relatedCategories as $relCat)
          <a href="{{ $relCat->url }}" style="display:inline-flex;align-items:center;padding:5px 12px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:20px;font-size:12.5px;font-weight:600;color:#334155;text-decoration:none">
            {{ $relCat->name }}
          </a>
        @endforeach
      </div>
    </div>
  @endif

  <!-- فیلتر استان‌ها (سئوی محلی) -->
  @if($activeProvinces->isNotEmpty())
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:14px 18px;margin-bottom:22px">
      <div style="font-size:12.5px;font-weight:800;color:#475569;margin-bottom:10px">
        📍 مشاهده {{ $category->name }} بر اساس استان:
      </div>
      <div style="display:flex;gap:7px;flex-wrap:wrap">
        <a href="{{ $category->url }}" style="display:inline-flex;align-items:center;padding:4px 11px;border-radius:20px;font-size:12px;font-weight:700;text-decoration:none;{{ !$province ? 'background:var(--primary);color:#fff;border:1px solid var(--primary)' : 'background:#f8fafc;color:#475569;border:1px solid #e2e8f0' }}">
          همه استان‌ها
        </a>
        @foreach($activeProvinces as $prov)
          @php
            $isActiveProv = $province && $province->id === $prov->id;
            $provComboUrl = route('categories.province', [
              'role' => $category->role_slug,
              'category' => slugify($category->name),
              'province' => slugify($prov->name),
            ]);
          @endphp
          <a href="{{ $provComboUrl }}" style="display:inline-flex;align-items:center;gap:5px;padding:4px 11px;border-radius:20px;font-size:12px;font-weight:600;text-decoration:none;{{ $isActiveProv ? 'background:var(--primary);color:#fff;border:1px solid var(--primary)' : 'background:#f8fafc;color:#334155;border:1px solid #e2e8f0' }}">
            <span>{{ $prov->name }}</span>
            <span style="font-size:11px;opacity:0.75">({{ $provinceCounts[$prov->id] ?? 0 }})</span>
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
        <span>در حال بارگذاری شرکت‌های بیشتر...</span>
      </div>
      <div id="infinite-end" style="{{ $companies->hasMorePages() ? 'display:none;' : 'display:block;' }}color:#94a3b8;font-size:13px;padding:8px 0">
        پایان فهرست شرکت‌های این دسته ({{ number_format($companies->total()) }} شرکت نمایش داده شد)
      </div>
    </div>
  @else
    <div class="card" style="padding:48px 24px;text-align:center;color:var(--muted);border-radius:16px">
      <div style="font-size:42px;margin-bottom:12px">🏭</div>
      <h3 style="margin:0 0 8px;font-size:18px;color:var(--text)">در حال حاضر شرکتی در این بخش ثبت نشده است</h3>
      <p style="margin:0 0 18px;font-size:14px">اگر در زمینه «{{ $category->name }}» فعالیت دارید، کسب‌وکار خود را رایگان ثبت کنید.</p>
      <a href="{{ route('companies.create') }}" class="btn small">ثبت رایگان شرکت</a>
    </div>
  @endif

  <!-- بخش محتوای سئو و تخصصی دسته‌بندی -->
  @if($category->clean_description)
    <article style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:24px 28px;margin-top:32px;line-height:1.9;color:#334155;font-size:14.5px">
      <h2 style="margin:0 0 12px;font-size:18px;font-weight:900;color:#0f172a">
        درباره {{ $h1Title }}
      </h2>
      <div class="category-seo-content">
        {!! $category->clean_description !!}
      </div>
    </article>
  @endif

  <!-- سوالات متداول سئو دسته‌بندی -->
  @if(!empty($faqItems))
    <section style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:24px 28px;margin-top:20px">
      <h2 style="margin:0 0 16px;font-size:18px;font-weight:900;color:#0f172a">
        ❓ سوالات متداول درباره {{ $category->smart_h1 }}
      </h2>
      <div style="display:flex;flex-direction:column;gap:12px">
        @foreach($faqItems as $faq)
          <details style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:14px 16px">
            <summary style="font-weight:800;font-size:14.5px;color:#0f172a;cursor:pointer">{{ $faq['q'] }}</summary>
            <div style="margin-top:10px;font-size:13.5px;color:#475569;line-height:1.8">{{ $faq['a'] }}</div>
          </details>
        @endforeach
      </div>
    </section>
  @endif
</div>

<style>
@keyframes spin { to { transform: rotate(360deg); } }
.category-seo-content p { margin: 0 0 10px; }
.category-seo-content p:last-child { margin-bottom: 0; }
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
      if (data.html) {
        grid.insertAdjacentHTML('beforeend', data.html);
      }
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
    if (entries[0].isIntersecting && nextPageUrl && !isLoading) {
      loadNextBatch();
    }
  }, { rootMargin: '250px' });
  observer.observe(trigger);
});
</script>
@endif
@endsection
