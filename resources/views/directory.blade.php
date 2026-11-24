@php
  $pageTitle = 'دایرکتوری شرکت‌ها';
  if (request('featured')) {
      $pageTitle = 'شرکت‌های ویژه';
  } elseif (request('cat') && $selectedCat = $categories->flatMap(fn($c) => collect([$c])->concat($c->children))->firstWhere('id', request('cat'))) {
      $pageTitle = 'دسته ' . $selectedCat->name;
  } elseif (request('province') && $selectedProv = $provinces->firstWhere('id', request('province'))) {
      $pageTitle = 'شرکت‌های استان ' . $selectedProv->name;
  } elseif (request('q')) {
      $pageTitle = 'جستجو برای "' . request('q') . '"';
  }
@endphp

@extends('layouts.app', ['title' => $pageTitle])

@section('content')
<!-- هدر بالای صفحه دایرکتوری -->
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:14px">
  <div>
    <h1 style="margin:0 0 6px;font-size:24px;font-weight:900;display:flex;align-items:center;gap:8px;color:#0f172a">
      @if(request('featured'))
        <span style="color:#f59e0b">★</span>
        <span>شرکت‌های ویژه</span>
      @elseif(request('cat') && isset($selectedCat))
        <span>📦</span>
        <span>{{ $selectedCat->name }}</span>
      @elseif(request('province') && isset($selectedProv))
        <span>📍</span>
        <span>کارخانجات و شرکت‌های استان {{ $selectedProv->name }}</span>
      @elseif(request('q'))
        <span>🔍</span>
        <span>نتایج جستجو: «{{ request('q') }}»</span>
      @else
        <span>🏭</span>
        <span>دایرکتوری کارخانجات و تولیدکنندگان</span>
      @endif
    </h1>
    <p style="margin:0;font-size:13.5px;color:#64748b">
      نمایش ۱۸ شرکت در هر بارگذاری · مجموعاً <strong>{{ number_format($companies->total()) }}</strong> شرکت و واحد تولیدی فعال
    </p>
  </div>

  <div style="display:flex;align-items:center;gap:10px">
    @if(request('featured'))
      <a href="{{ route('directory') }}" class="btn ghost small" style="display:inline-flex;align-items:center;gap:6px">
        <span>مشاهده همه شرکت‌ها</span>
        <span>←</span>
      </a>
    @else
      <a href="{{ route('directory', ['featured' => 1]) }}" class="btn small" style="background:#fef3c7;color:#92400e;border:1.5px solid #fcd34d;font-weight:800;display:inline-flex;align-items:center;gap:6px">
        <span style="color:#f59e0b">★</span>
        <span>مشاهده شرکت‌های ویژه</span>
      </a>
    @endif
  </div>
</div>

<!-- فرم فیلتر پیشرفته و جستجو -->
<form class="directory-filter-bar" method="get" action="{{ route('directory') }}">
  @if(request('featured'))
    <input type="hidden" name="featured" value="1">
  @endif

  <div class="filter-input-wrap">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="نام شرکت، محصول یا رسته...">
  </div>

  <div class="filter-select-wrap">
    <select name="cat" aria-label="انتخاب دسته‌بندی">
      <option value="">همه دسته‌بندی‌ها</option>
      @foreach($categories as $c)
        <option value="{{ $c->id }}" @selected(request('cat')==$c->id)>{{ $c->name }}</option>
        @foreach($c->children as $ch)
          <option value="{{ $ch->id }}" @selected(request('cat')==$ch->id)>— {{ $ch->name }}</option>
        @endforeach
      @endforeach
    </select>
  </div>

  <div class="filter-select-wrap">
    <select name="province" aria-label="انتخاب استان">
      <option value="">همه استان‌ها</option>
      @foreach($provinces as $p)
        <option value="{{ $p->id }}" @selected(request('province')==$p->id)>{{ $p->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="filter-select-wrap">
    <select name="city" aria-label="انتخاب شهر">
      <option value="">همه شهرها</option>
      @foreach($cities as $c)
        <option value="{{ $c->id }}" @selected(request('city')==$c->id)>{{ $c->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="filter-actions-wrap">
    <button type="submit" class="btn" style="padding:10px 22px;font-weight:800;font-size:13.5px;border-radius:9px;white-space:nowrap">
      اعمال فیلتر
    </button>
    
    @if(request()->hasAny(['q', 'cat', 'province', 'city', 'featured']))
      <a href="{{ route('directory') }}" class="btn ghost small" style="color:#ef4444!important;border-color:#fecaca!important;background:#fff5f5" title="پاکسازی فیلترها">
        ✕
      </a>
    @endif
  </div>
</form>

<!-- شبکه ۶ ستونه کارت‌های شرکت‌ها -->
<div id="directoryCompaniesGrid" class="directory-grid">
  @forelse($companies as $company)
    @include('partials.card', ['company' => $company])
  @empty
    <div style="grid-column:1/-1;text-align:center;padding:50px 20px;background:#ffffff;border:1px dashed #cbd5e1;border-radius:14px;margin:10px 0">
      <span style="font-size:48px;display:block;margin-bottom:12px">🔍</span>
      <h3 style="margin:0 0 8px;font-size:17px;font-weight:800;color:#0f172a">شرکتی با این مشخصات یافت نشد</h3>
      <p style="margin:0 0 18px;font-size:13.5px;color:#64748b">عبارت جستجو یا فیلتر دسته‌بندی و شهر را تغییر دهید تا نتایج بیشتری مشاهده کنید.</p>
      <a href="{{ route('directory') }}" class="btn ghost small">مشاهده همه شرکت‌های فعال</a>
    </div>
  @endforelse
</div>

<!-- نگهدارنده اسکرول نامحدود (Infinite Scroll Sentinel & Loading) -->
<div id="infiniteScrollSentinel" style="height:20px;margin:20px 0 10px"></div>

<!-- انیمیشن چرخان بارگذاری ۱۸ شرکت بعدی -->
<div id="infiniteLoadingIndicator" style="display:none;text-align:center;padding:26px 0">
  <div class="infinite-loader-spinner"></div>
  <span style="font-size:13.5px;font-weight:700;color:#475569;margin-top:10px;display:block">
    در حال بارگذاری ۱۸ شرکت بعدی...
  </span>
</div>

<!-- دکمه کمکی بارگذاری دستی ۱۸ شرکت بیشتر -->
@if($companies->hasMorePages())
  <div id="manualLoadMoreWrap" style="text-align:center;margin:24px 0 32px">
    <button type="button" id="loadMoreBtn" class="btn" style="padding:12px 34px;font-size:14px;font-weight:800;border-radius:10px;display:inline-flex;align-items:center;gap:8px" onclick="loadNextDirectoryPage()">
      <span>مشاهده ۱۸ شرکت بیشتر</span>
      <span style="font-size:16px">↓</span>
    </button>
  </div>
@endif

<!-- پیام رسیدن به انتهای لیست -->
<div id="infiniteScrollEnd" style="@if(!$companies->hasMorePages() && $companies->count() > 0) display:block; @else display:none; @endif text-align:center;padding:32px 0 20px;color:#94a3b8;font-size:13px;font-weight:700">
  ✓ به انتهای فهرست شرکت‌ها رسیدید (مجموع {{ number_format($companies->total()) }} شرکت)
</div>

<!-- پیوند صفحه‌بندی سنتی جهت ربات‌ها و سئو در صورت عدم اجرای جاوااسکریپت -->
<noscript>
  <div style="margin-top:24px;text-align:center">
    {{ $companies->links() }}
  </div>
</noscript>

<style>
/* ==========================================================================
   استایل‌های نوار فیلتر و شبکه ۶ ستونه دایرکتوری و بارگذاری خودکار
   ========================================================================== */
.directory-filter-bar {
  display: flex;
  align-items: center;
  gap: 10px;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  padding: 12px 14px;
  margin-bottom: 24px;
  flex-wrap: wrap;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
}
.filter-input-wrap {
  flex: 2 1 200px;
}
.filter-input-wrap input {
  width: 100%;
  padding: 10px 14px;
  border: 1.5px solid #cbd5e1;
  border-radius: 9px;
  font-family: inherit;
  font-size: 13.5px;
  box-sizing: border-box;
  outline: none;
  transition: border-color 0.2s;
}
.filter-input-wrap input:focus {
  border-color: var(--primary);
}
.filter-select-wrap {
  flex: 1 1 140px;
}
.filter-select-wrap select {
  width: 100%;
  padding: 10px 12px;
  border: 1.5px solid #cbd5e1;
  border-radius: 9px;
  font-family: inherit;
  font-size: 13px;
  box-sizing: border-box;
  background: #ffffff;
  outline: none;
  cursor: pointer;
  transition: border-color 0.2s;
}
.filter-select-wrap select:focus {
  border-color: var(--primary);
}
.filter-actions-wrap {
  display: flex;
  align-items: center;
  gap: 6px;
}

/* شبکه ۶ ستونه شرکت‌ها */
.directory-grid {
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  gap: 14px;
  width: 100%;
  box-sizing: border-box;
}
@media (max-width: 1100px) {
  .directory-grid {
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
  }
}
@media (max-width: 800px) {
  .directory-grid {
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
  }
}
@media (max-width: 560px) {
  .directory-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
  }
}
@media (max-width: 360px) {
  .directory-grid {
    grid-template-columns: 1fr;
  }
}

/* انیمیشن چرخان لودینگ ۱۸ شرکت بعدی */
.infinite-loader-spinner {
  display: inline-block;
  width: 34px;
  height: 34px;
  border: 3.5px solid #e2e8f0;
  border-top-color: var(--primary);
  border-radius: 50%;
  animation: infSpin 0.75s linear infinite;
}
@keyframes infSpin {
  to { transform: rotate(360deg); }
}
</style>

<script>
/* ==========================================================================
   اسکریپت بارگذاری خودکار نامحدود ۱۸ به ۱۸ شرکت‌ها (Infinite Scroll)
   ========================================================================== */
(function() {
  let nextPageUrl = @json($companies->nextPageUrl());
  let hasMorePages = @json($companies->hasMorePages());
  let isLoading = false;

  const sentinel = document.getElementById('infiniteScrollSentinel');
  const loadingEl = document.getElementById('infiniteLoadingIndicator');
  const manualWrap = document.getElementById('manualLoadMoreWrap');
  const loadBtn = document.getElementById('loadMoreBtn');
  const endEl = document.getElementById('infiniteScrollEnd');
  const grid = document.getElementById('directoryCompaniesGrid');

  window.loadNextDirectoryPage = function() {
    if (!hasMorePages || isLoading || !nextPageUrl) return;

    isLoading = true;
    if (loadingEl) loadingEl.style.display = 'block';
    if (loadBtn) {
      loadBtn.disabled = true;
      loadBtn.style.opacity = '0.6';
      loadBtn.innerHTML = 'در حال بارگذاری ۱۸ شرکت...';
    }

    fetch(nextPageUrl, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      }
    })
    .then(res => {
      if (!res.ok) throw new Error('خطا در بارگذاری اطلاعات');
      return res.json();
    })
    .then(data => {
      if (data.html && grid) {
        // ایجاد المان موقت و الحاق روان به شبکه
        const temp = document.createElement('div');
        temp.innerHTML = data.html;
        while (temp.firstChild) {
          grid.appendChild(temp.firstChild);
        }
      }

      hasMorePages = Boolean(data.has_more);
      nextPageUrl = data.next_page_url;

      // به‌روزرسانی پارامتر صفحه در مرورگر بدون رفرش (History API)
      if (data.current_page) {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('page', data.current_page);
        window.history.replaceState({}, '', currentUrl.toString());
      }

      if (!hasMorePages) {
        if (manualWrap) manualWrap.style.display = 'none';
        if (endEl) endEl.style.display = 'block';
        if (observer && sentinel) observer.unobserve(sentinel);
      } else {
        if (loadBtn) {
          loadBtn.disabled = false;
          loadBtn.style.opacity = '1';
          loadBtn.innerHTML = '<span>مشاهده ۱۸ شرکت بیشتر</span> <span style="font-size:16px">↓</span>';
        }
      }
    })
    .catch(err => {
      console.error('Infinite scroll error:', err);
      if (loadBtn) {
        loadBtn.disabled = false;
        loadBtn.style.opacity = '1';
        loadBtn.innerHTML = 'خطا در بارگذاری · کلیک جهت تلاش مجدد';
      }
    })
    .finally(() => {
      isLoading = false;
      if (loadingEl) loadingEl.style.display = 'none';
    });
  };

  // نظارت بر رسیدن کاربر به انتهای لیست با IntersectionObserver
  let observer = null;
  if ('IntersectionObserver' in window && sentinel) {
    observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting && hasMorePages && !isLoading) {
          window.loadNextDirectoryPage();
        }
      });
    }, {
      rootMargin: '350px 0px' // ۳۵۰ پیکسل قبل از انتهای صفحه بارگذاری آغاز می‌شود
    });

    observer.observe(sentinel);
  }
})();
</script>
@endsection
