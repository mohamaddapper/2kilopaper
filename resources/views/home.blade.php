@extends('layouts.app')

@section('hero')
<!-- اسلایدر بنرهای بالای صفحه اصلی (ارتفاع ۵۰۰ پیکسل با استایل ورق‌خور و نمایش بنرهای قبل و بعد) -->
<section class="home-slider-section">
  
  <!-- دکمه‌های ناوبری اسلایدر در کناره‌های چپ و راست -->
  <button type="button" class="home-slider-arrow home-slider-prev" onclick="moveHomeSlider(-1)" aria-label="بنر قبلی" title="بنر قبلی">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
  </button>
  <button type="button" class="home-slider-arrow home-slider-next" onclick="moveHomeSlider(1)" aria-label="بنر بعدی" title="بنر بعدی">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
  </button>

  <!-- کادر تمام‌عرض اسلایدر -->
  <div class="home-slider-wrapper" id="homeSliderWrapper">
    <div class="home-slider-track" id="homeSliderTrack">

      <!-- آیتم ۰: کلون اسلاید ۵ (جهت چرخش بی‌نهایت بدون پرش) -->
      <div class="home-slider-item" data-clone="5">
        <div class="home-banner-card" style="background:linear-gradient(135deg, #881337 0%, #be123c 55%, #e11d48 100%)">
          <div class="banner-decor decor-1"></div>
          <div class="banner-decor decor-2"></div>
          <div class="home-banner-inner">
            <div class="home-banner-content">
              <div class="home-banner-badge">
                <span>📢</span>
                <span>تبلیغات هدفمند B2B</span>
              </div>
              <h2 class="home-banner-title">برند خود را به هزاران خریدار و فعال صنعت معرفی کنید</h2>
              <p class="home-banner-desc">جایگاه‌های اختصاصی بنر، اسپانسری دسته‌ها و دیده‌شدن توسط فعالان صنعت چاپ، کارتن‌سازی و بسته‌بندی</p>
              <div class="home-banner-action">
                <a href="{{ route('advertising') }}" class="btn home-banner-btn" style="color:#be123c!important">
                  <span>تعرفه‌ها و رزرو تبلیغات</span>
                  <span style="font-size:16px">←</span>
                </a>
              </div>
            </div>
            <div class="home-banner-graphic">🎯</div>
          </div>
        </div>
      </div>

      <!-- اسلاید ۱: دایرکتوری و جستجوی جامع شرکت‌ها -->
      <div class="home-slider-item" data-index="0">
        <div class="home-banner-card" style="background:linear-gradient(135deg, #0f172a 0%, #1e3a8a 55%, #0284c7 100%)">
          <div class="banner-decor decor-1"></div>
          <div class="banner-decor decor-2"></div>
          <div class="home-banner-inner">
            <div class="home-banner-content">
              <div class="home-banner-badge">
                <span>📦</span>
                <span>دایرکتوری تخصصی صنعت کاغذ، کارتن و چاپ</span>
              </div>
              <h2 class="home-banner-title">بزرگ‌ترین بانک اطلاعاتی کارخانجات و تولیدکنندگان ایران</h2>
              <p class="home-banner-desc">دسترسی به اطلاعات تماس، نشانی، محصولات و مشخصات فنی بیش از ۹۰۰ شرکت معتبر در سراسر کشور</p>
              <div class="home-banner-action">
                <a href="{{ route('directory') }}" class="btn home-banner-btn" style="color:#0369a1!important">
                  <span>مشاهده دایرکتوری شرکت‌ها</span>
                  <span style="font-size:16px">←</span>
                </a>
              </div>
            </div>
            <div class="home-banner-graphic">🏭</div>
          </div>
        </div>
      </div>

      <!-- اسلاید ۲: ارتقای اشتراک طلایی و فروش ویژه -->
      <div class="home-slider-item" data-index="1">
        <div class="home-banner-card" style="background:linear-gradient(135deg, #451a03 0%, #92400e 55%, #d97706 100%)">
          <div class="banner-decor decor-1"></div>
          <div class="banner-decor decor-2"></div>
          <div class="home-banner-inner">
            <div class="home-banner-content">
              <div class="home-banner-badge">
                <span>⭐</span>
                <span>دیده‌شدن حداکثری و افزایش فروش</span>
              </div>
              <h2 class="home-banner-title">با اشتراک طلایی، در رتبه اول نتایج و دید خریداران باشید</h2>
              <p class="home-banner-desc">نمایش نشان ویژه، اولویت در نتایج جستجو، حذف تبلیغات رقبا و دسترسی مستقیم به خریداران عمده کاغذ و مقوا</p>
              <div class="home-banner-action">
                <a href="{{ route('subscription.fee') }}" class="btn home-banner-btn" style="color:#b45309!important">
                  <span>مشاهده پلن‌ها و تعرفه‌ها</span>
                  <span style="font-size:16px">←</span>
                </a>
              </div>
            </div>
            <div class="home-banner-graphic">👑</div>
          </div>
        </div>
      </div>

      <!-- اسلاید ۳: کاریابی و استخدام تخصصی صنعت -->
      <div class="home-slider-item" data-index="2">
        <div class="home-banner-card" style="background:linear-gradient(135deg, #064e3b 0%, #047857 55%, #059669 100%)">
          <div class="banner-decor decor-1"></div>
          <div class="banner-decor decor-2"></div>
          <div class="home-banner-inner">
            <div class="home-banner-content">
              <div class="home-banner-badge">
                <span>💼</span>
                <span>کاریابی و استخدام صنایع چاپ و بسته‌بندی</span>
              </div>
              <h2 class="home-banner-title">مرجع فرصت‌های شغلی و جذب نیروهای متخصص</h2>
              <p class="home-banner-desc">ارسال رزومه برای برترین کارخانجات یا ثبت آگهی جهت استخدام سریع اپراتور دستگاه، کارشناس فروش و مدیر تولید</p>
              <div class="home-banner-action">
                <a href="{{ route('jobs.index') }}" class="btn home-banner-btn" style="color:#047857!important">
                  <span>مشاهده فرصت‌های شغلی</span>
                  <span style="font-size:16px">←</span>
                </a>
              </div>
            </div>
            <div class="home-banner-graphic">🤝</div>
          </div>
        </div>
      </div>

      <!-- اسلاید ۴: ثبت رایگان شرکت و کارگاه -->
      <div class="home-slider-item" data-index="3">
        <div class="home-banner-card" style="background:linear-gradient(135deg, #312e81 0%, #4338ca 55%, #6366f1 100%)">
          <div class="banner-decor decor-1"></div>
          <div class="banner-decor decor-2"></div>
          <div class="home-banner-inner">
            <div class="home-banner-content">
              <div class="home-banner-badge">
                <span>🚀</span>
                <span>ویژه مدیران و تولیدکنندگان</span>
              </div>
              <h2 class="home-banner-title">شرکت یا کارگاه خود را رایگان در دایرکتوری ثبت کنید</h2>
              <p class="home-banner-desc">ایجاد صفحه اختصاصی با کاتالوگ PDF، عکس نمونه محصولات، فیلم کارخانه و نمایش مستقیم شماره‌های تماس</p>
              <div class="home-banner-action">
                <a href="{{ route('companies.create') }}" class="btn home-banner-btn" style="color:#4338ca!important">
                  <span>+ ثبت رایگان شرکت</span>
                  <span style="font-size:16px">←</span>
                </a>
              </div>
            </div>
            <div class="home-banner-graphic">📑</div>
          </div>
        </div>
      </div>

      <!-- اسلاید ۵: تبلیغات بنری و معرفی برند -->
      <div class="home-slider-item" data-index="4">
        <div class="home-banner-card" style="background:linear-gradient(135deg, #881337 0%, #be123c 55%, #e11d48 100%)">
          <div class="banner-decor decor-1"></div>
          <div class="banner-decor decor-2"></div>
          <div class="home-banner-inner">
            <div class="home-banner-content">
              <div class="home-banner-badge">
                <span>📢</span>
                <span>تبلیغات هدفمند B2B</span>
              </div>
              <h2 class="home-banner-title">برند خود را به هزاران خریدار و فعال صنعت معرفی کنید</h2>
              <p class="home-banner-desc">جایگاه‌های اختصاصی بنر، اسپانسری دسته‌ها و دیده‌شدن توسط فعالان صنعت چاپ، کارتن‌سازی و بسته‌بندی</p>
              <div class="home-banner-action">
                <a href="{{ route('advertising') }}" class="btn home-banner-btn" style="color:#be123c!important">
                  <span>تعرفه‌ها و رزرو تبلیغات</span>
                  <span style="font-size:16px">←</span>
                </a>
              </div>
            </div>
            <div class="home-banner-graphic">🎯</div>
          </div>
        </div>
      </div>

      <!-- آیتم ۶: کلون اسلاید ۱ (جهت چرخش بی‌نهایت بدون پرش) -->
      <div class="home-slider-item" data-clone="1">
        <div class="home-banner-card" style="background:linear-gradient(135deg, #0f172a 0%, #1e3a8a 55%, #0284c7 100%)">
          <div class="banner-decor decor-1"></div>
          <div class="banner-decor decor-2"></div>
          <div class="home-banner-inner">
            <div class="home-banner-content">
              <div class="home-banner-badge">
                <span>📦</span>
                <span>دایرکتوری تخصصی صنعت کاغذ، کارتن و چاپ</span>
              </div>
              <h2 class="home-banner-title">بزرگ‌ترین بانک اطلاعاتی کارخانجات و تولیدکنندگان ایران</h2>
              <p class="home-banner-desc">دسترسی به اطلاعات تماس، نشانی، محصولات و مشخصات فنی بیش از ۹۰۰ شرکت معتبر در سراسر کشور</p>
              <div class="home-banner-action">
                <a href="{{ route('directory') }}" class="btn home-banner-btn" style="color:#0369a1!important">
                  <span>مشاهده دایرکتوری شرکت‌ها</span>
                  <span style="font-size:16px">←</span>
                </a>
              </div>
            </div>
            <div class="home-banner-graphic">🏭</div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- نشانگرهای دایره‌ای (۵ دات در پایین اسلایدر) -->
  <div class="home-slider-dots">
    <span class="home-slider-dot active" onclick="goToHomeSlide(0)" title="بنر ۱"></span>
    <span class="home-slider-dot" onclick="goToHomeSlide(1)" title="بنر ۲"></span>
    <span class="home-slider-dot" onclick="goToHomeSlide(2)" title="بنر ۳"></span>
    <span class="home-slider-dot" onclick="goToHomeSlide(3)" title="بنر ۴"></span>
    <span class="home-slider-dot" onclick="goToHomeSlide(4)" title="بنر ۵"></span>
  </div>

</section>
@endsection

@section('content')

<!-- ۱. بخش جستجوی متمرکز (دنبال چی می‌گردی؟) با الهام از ساختار سایت اصلی -->
<section class="home-search-section">
  <div class="search-title-wrap">
    <div class="search-title-line"></div>
    <h2 class="search-title-text">دنبال چی می‌گردی؟</h2>
    <div class="search-title-line"></div>
  </div>
  <p class="search-subtitle">جستجو در میان بیش از ۹۰۰ کارخانه، تولیدکننده و تأمین‌کننده فعال صنعت کارتن، چاپ و بسته‌بندی ایران</p>

  <form class="home-search-bar" action="{{ route('directory') }}" method="get">
    <div class="search-input-field">
      <span class="search-icon">🔍</span>
      <input type="text" name="q" placeholder="جستجو برای نام شرکت، نوع جعبه، ورق کارتن، رسته یا محصول..." value="{{ request('q') }}">
    </div>

    <div class="search-select-field">
      <select name="cat" aria-label="انتخاب دسته‌بندی">
        <option value="">همه دسته‌بندی‌ها</option>
        @foreach($categories as $c)
          <option value="{{ $c->id }}" @selected(request('cat') == $c->id)>{{ $c->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="search-select-field">
      <select name="province" aria-label="انتخاب استان">
        <option value="">همه استان‌ها</option>
        @foreach($searchProvinces as $p)
          <option value="{{ $p->id }}" @selected(request('province') == $p->id)>{{ $p->name }}</option>
        @endforeach
      </select>
    </div>

    <button type="submit" class="btn search-submit-btn">
      <span>جستجو</span>
    </button>
  </form>

  <!-- برچسب‌ها و چیپ‌های جستجوی پرطرفدار صنعت -->
  <div class="search-chips-wrap">
    <span class="search-chips-label">جستجوهای پرطرفدار:</span>
    <a href="{{ route('directory', ['q' => 'جعبه کارتن']) }}" class="search-chip">#جعبه_کارتن</a>
    <a href="{{ route('directory', ['q' => 'ورق ۵ لایه']) }}" class="search-chip">#ورق_۵_لایه</a>
    <a href="{{ route('directory', ['q' => 'ورق ۳ لایه']) }}" class="search-chip">#ورق_۳_لایه</a>
    <a href="{{ route('directory', ['q' => 'کاغذ کرافت']) }}" class="search-chip">#کاغذ_کرافت</a>
    <a href="{{ route('directory', ['q' => 'فلوتینگ']) }}" class="search-chip">#فلوتینگ</a>
    <a href="{{ route('directory', ['q' => 'چاپ افست']) }}" class="search-chip">#چاپ_افست</a>
    <a href="{{ route('directory', ['q' => 'چاپ فلکسو']) }}" class="search-chip">#چاپ_فلکسو</a>
    <a href="{{ route('directory', ['q' => 'ماشین آلات']) }}" class="search-chip">#ماشین‌آلات</a>
    <a href="{{ route('directory', ['q' => 'چاپ لیبل']) }}" class="search-chip">#چاپ_لیبل</a>
  </div>
</section>

<!-- ۲. بخش ۸ رسته صنعتی اصلی (شبکه دسترسی سریع با تعداد شرکت‌ها) -->
<section class="home-section">
  <div class="home-section-header">
    <div class="header-right">
      <h2 class="home-section-title">رسته‌های اصلی صنعت</h2>
      <span class="home-section-tag">پرطرفدارترین بخش‌ها</span>
    </div>
    <a href="{{ route('directory') }}" class="home-section-link">
      <span>مشاهده همه دسته‌بندی‌ها</span>
      <span style="font-size:16px">←</span>
    </a>
  </div>

  @php
    $catIcons = [
        41 => ['icon' => '📦', 'color' => '#2563eb', 'bg' => '#eff6ff'], // جعبه کارتن
        130 => ['icon' => '📢', 'color' => '#0891b2', 'bg' => '#ecfeff'], // چاپ تبلیغاتی
        124 => ['icon' => '🖨️', 'color' => '#4f46e5', 'bg' => '#eef2ff'], // چاپ افست
        128 => ['icon' => '🏷️', 'color' => '#059669', 'bg' => '#ecfdf5'], // چاپ لیبل و برچسب
        129 => ['icon' => '🛍️', 'color' => '#9333ea', 'bg' => '#faf5ff'], // چاپ جعبه و بسته بندی
        40 => ['icon' => '📄', 'color' => '#d97706', 'bg' => '#fffbeb'], // ورق کارتن
        62 => ['icon' => '🎁', 'color' => '#e11d48', 'bg' => '#fff1f2'], // جعبه مقوایی
        56 => ['icon' => '⚙️', 'color' => '#475569', 'bg' => '#f1f5f9'], // ماشین آلات
    ];
  @endphp

  <div class="core-cat-grid">
    @foreach($coreCategories as $cat)
      @php $meta = $catIcons[$cat->id] ?? ['icon' => '🏭', 'color' => '#1f5fa8', 'bg' => '#f0f7ff']; @endphp
      <a href="{{ route('directory', ['cat' => $cat->id]) }}" class="core-cat-card">
        <div class="core-cat-icon" style="background:{{ $meta['bg'] }};color:{{ $meta['color'] }}">
          <span>{{ $meta['icon'] }}</span>
        </div>
        <div class="core-cat-info">
          <h3 class="core-cat-name">{{ $cat->name }}</h3>
          <span class="core-cat-count">{{ number_format($cat->companies_count) }} شرکت فعال</span>
        </div>
        <span class="core-cat-arrow" style="color:{{ $meta['color'] }}">←</span>
      </a>
    @endforeach
  </div>
</section>

<!-- ۳. بخش ۳ بنر تبلیغاتی هم‌سطح (مشابه بنرهای ۳ گانه صفحه اصلی ۲ کیلو کاغذ) -->
<section class="home-section">
  <div class="triple-banners-grid">
    
    <!-- بنر ۱: نگین کوثر پارس -->
    <div class="ad-promo-card promo-blue">
      <div class="promo-badge">تولیدکننده برتر صنعت</div>
      <h3 class="promo-title">نگین کوثر پارس</h3>
      <p class="promo-desc">تولید انواع کاغذ تست لاینر، شبه‌کرافت و فلوتینگ با استانداردهای صنعتی و کیفیت پایدار</p>
      <div class="promo-action">
        <a href="{{ route('directory', ['q' => 'نگین کوثر پارس']) }}" class="promo-btn">
          <span>اطلاعات تماس و استعلام</span>
          <span>←</span>
        </a>
      </div>
    </div>

    <!-- بنر ۲: اپلیکیشن ۲ کیلو کاغذ -->
    <div class="ad-promo-card promo-green">
      <div class="promo-badge">نسخه تلفن همراه</div>
      <h3 class="promo-title">اپ ۲ کیلو کاغذ</h3>
      <p class="promo-desc">دسترسی لحظه‌ای به شماره تلفن کارخانجات، نقشه جغرافیایی واحدها و ثبت آگهی‌های اختصاصی</p>
      <div class="promo-action">
        <a href="#app-download" class="promo-btn">
          <span>دانلود مستقیم اپلیکیشن</span>
          <span>←</span>
        </a>
      </div>
    </div>

    <!-- بنر ۳: سرویس پیامکی و استعلام آنلاین -->
    <div class="ad-promo-card promo-amber">
      <div class="promo-badge">استعلام بدون واسطه</div>
      <h3 class="promo-title">سامانه هوشمند استعلام</h3>
      <p class="promo-desc">دریافت مستقیم پیش‌فاکتور قیمت ورق و کارتن از تولیدکنندگان سراسر کشور بدون پرداخت کمیسیون</p>
      <div class="promo-action">
        <a href="{{ route('directory') }}" class="promo-btn">
          <span>شروع استعلام آنلاین</span>
          <span>←</span>
        </a>
      </div>
    </div>

  </div>
</section>

<!-- ۴. بخش شرکت‌های ویژه (ویژه‌های طلایی و نقره‌ای با پاسخگویی سریع) -->
@if($featured->count())
<section class="home-section">
  <div class="home-section-header">
    <div class="header-right">
      <h2 class="home-section-title">
        <span style="color:#f59e0b;margin-left:4px">★</span>
        شرکت‌های ویژه و منتخب صنعت
      </h2>
      <span class="home-section-tag" style="background:#fef3c7;color:#92400e;border-color:#fcd34d">تأییدشده و پاسخگو</span>
    </div>
    <a href="{{ route('directory') }}" class="home-section-link">
      <span>مشاهده همه شرکت‌ها</span>
      <span style="font-size:16px">←</span>
    </a>
  </div>

  <div class="grid">
    @foreach($featured as $company)
      @include('partials.card', ['company' => $company])
    @endforeach
  </div>

  <!-- نوار ترغیب مدیران به ارتقای اشتراک -->
  <div class="vip-cta-bar">
    <div class="vip-cta-text">
      <strong>شما هم مدیر یا تولیدکننده در این صنعت هستید؟</strong>
      <span>با دریافت اشتراک طلایی، در رتبه اول نتایج جستجو و دید هزاران خریدار روزانه قرار بگیرید.</span>
    </div>
    <a href="{{ route('subscription.fee') }}" class="btn vip-cta-btn">
      <span>تعرفه‌های اشتراک طلایی</span>
      <span>←</span>
    </a>
  </div>
</section>
@endif

<!-- ۵. بخش کاریابی و استخدام دو ستونه (آگهی‌های استخدام + رزومه کارجویان) -->
<section class="home-section">
  <div class="jobs-dual-grid">
    
    <!-- ستون راست: آگهی‌های استخدام -->
    <div class="jobs-col">
      <div class="jobs-col-head">
        <div style="display:flex;align-items:center;gap:8px">
          <span style="font-size:20px">💼</span>
          <h3 style="margin:0;font-size:17px;font-weight:800;color:#0f172a">فرصت‌های شغلی جدید</h3>
        </div>
        <a href="{{ route('jobs.index') }}" style="font-size:13px;font-weight:700;color:var(--primary)">همه آگهی‌ها ←</a>
      </div>

      <div class="jobs-list">
        @forelse($recentJobs as $job)
          <a href="{{ route('jobs.show', $job) }}" class="job-item-card">
            <div class="job-item-main">
              <h4 class="job-item-title">{{ $job->title }}</h4>
              <p class="job-item-company">
                {{ $job->company->name ?? 'کارخانه معتبر صنعتی' }} · 📍 {{ $job->province->name ?? 'ایران' }}
              </p>
            </div>
            <div class="job-item-badges">
              @if($job->is_immediate)
                <span class="badge" style="background:#fee2e2;color:#b91c1c;font-size:11px">فوری</span>
              @endif
              <span class="badge" style="background:#eef2ff;color:#4338ca;font-size:11px">
                {{ $job->job_type ?? 'تمام وقت' }}
              </span>
            </div>
          </a>
        @empty
          <div class="job-empty-box">
            <p>در حال حاضر آگهی استخدامی فعالی ثبت نشده است.</p>
          </div>
        @endforelse
      </div>

      <div class="jobs-col-footer">
        <a href="{{ route('employer.jobs.create') }}" class="btn ghost small" style="width:100%;text-align:center">
          + ثبت رایگان آگهی استخدام نیرو
        </a>
      </div>
    </div>

    <!-- ستون چپ: بانک رزومه متخصصین صنعت -->
    <div class="jobs-col">
      <div class="jobs-col-head">
        <div style="display:flex;align-items:center;gap:8px">
          <span style="font-size:20px">👤</span>
          <h3 style="margin:0;font-size:17px;font-weight:800;color:#0f172a">رزومه متخصصین و کارجویان</h3>
        </div>
        <a href="{{ route('jobs.index') }}" style="font-size:13px;font-weight:700;color:var(--primary)">بانک کارجویان ←</a>
      </div>

      <div class="jobs-list">
        @forelse($recentResumes as $resume)
          <div class="job-item-card" style="cursor:default">
            <div class="job-item-main">
              <h4 class="job-item-title">{{ $resume->full_name }}</h4>
              <p class="job-item-company">
                {{ $resume->education ?? 'متخصص صنعت کارتن و چاپ' }} · 📍 {{ $resume->province->name ?? 'ایران' }}
              </p>
            </div>
            <div class="job-item-badges">
              <span class="badge" style="background:#ecfdf5;color:#047857;font-size:11px">
                {{ $resume->experience_years ? $resume->experience_years . ' سال سابقه' : 'آماده به کار' }}
              </span>
            </div>
          </div>
        @empty
          <div class="job-empty-box">
            <p>در حال حاضر رزومه‌ای برای نمایش وجود ندارد.</p>
          </div>
        @endforelse
      </div>

      <div class="jobs-col-footer">
        <a href="{{ route('jobs.index') }}" class="btn ghost small" style="width:100%;text-align:center">
          + ثبت رزومه کاری برای شرکت‌ها
        </a>
      </div>
    </div>

  </div>
</section>

<!-- ۶. بخش ۲ بنر عریض میانی (حامیان برتر صنعت مشابه ۲ کیلو کاغذ) -->
<section class="home-section">
  <div class="dual-banners-grid">
    
    <!-- بنر ۱: شهباز صنعت آسیا -->
    <div class="dual-banner-box banner-shahbaz">
      <div class="dual-banner-content">
        <span class="dual-banner-badge">تولیدکننده تخصصی ورق</span>
        <h3 class="dual-banner-title">شهباز صنعت آسیا</h3>
        <p class="dual-banner-desc">تولیدکننده انواع سینگل فیس فلوت‌های E, B, C, F در عرض ۱۰۰ تا ۱۶۰ سانتیمتر به صورت رول و شیت</p>
        <a href="{{ route('directory', ['q' => 'شهباز صنعت آسیا']) }}" class="btn dual-banner-btn">
          <span>مشاهده و سفارش</span>
          <span>←</span>
        </a>
      </div>
    </div>

    <!-- بنر ۲: مواد شیمیایی و سولفات آلومینیوم -->
    <div class="dual-banner-box banner-alum">
      <div class="dual-banner-content">
        <span class="dual-banner-badge">مواد اولیه و شیمیایی</span>
        <h3 class="dual-banner-title">تولید سولفات آلومینیوم</h3>
        <p class="dual-banner-desc">تولید منعقدکننده‌های ویژه صنایع کاغذسازی، مقوا و تصفیه پساب کارخانجات با خلوص بالا و مطابق استاندارد</p>
        <a href="{{ route('directory', ['q' => 'سولفات']) }}" class="btn dual-banner-btn">
          <span>استعلام قیمت و ارسال نمونه</span>
          <span>←</span>
        </a>
      </div>
    </div>

  </div>
</section>

<!-- ۷. نمایشگاه تفکیکی صنعت با تب‌های زنده (Vanilla JS) -->
<section class="home-section">
  <div class="home-section-header">
    <div class="header-right">
      <h2 class="home-section-title">نمایشگاه تفکیکی شرکت‌ها</h2>
      <span class="home-section-tag">بر اساس رسته فعالیت</span>
    </div>
  </div>

  <!-- تب‌های انتخاب رسته -->
  <div class="showcase-tabs">
    <button type="button" class="showcase-tab active" onclick="switchShowcaseTab('carton', this)">
      <span>📦</span>
      <span>ورق و کارتن‌سازی</span>
    </button>
    <button type="button" class="showcase-tab" onclick="switchShowcaseTab('paper', this)">
      <span>📜</span>
      <span>کاغذ و مقوا</span>
    </button>
    <button type="button" class="showcase-tab" onclick="switchShowcaseTab('print', this)">
      <span>🖨️</span>
      <span>چاپ و بسته‌بندی</span>
    </button>
    <button type="button" class="showcase-tab" onclick="switchShowcaseTab('machinery', this)">
      <span>⚙️</span>
      <span>ماشین‌آلات و تجهیزات</span>
    </button>
  </div>

  <!-- محتوای تب ۱: کارتن -->
  <div class="showcase-content active" id="tab-carton">
    <div class="grid">
      @forelse($cartonCompanies as $company)
        @include('partials.card', ['company' => $company])
      @empty
        <p style="color:var(--muted);grid-column:1/-1;text-align:center;padding:20px">شرکتی در این دسته یافت نشد.</p>
      @endforelse
    </div>
  </div>

  <!-- محتوای تب ۲: کاغذ -->
  <div class="showcase-content" id="tab-paper">
    <div class="grid">
      @forelse($paperCompanies as $company)
        @include('partials.card', ['company' => $company])
      @empty
        <p style="color:var(--muted);grid-column:1/-1;text-align:center;padding:20px">شرکتی در این دسته یافت نشد.</p>
      @endforelse
    </div>
  </div>

  <!-- محتوای تب ۳: چاپ -->
  <div class="showcase-content" id="tab-print">
    <div class="grid">
      @forelse($printCompanies as $company)
        @include('partials.card', ['company' => $company])
      @empty
        <p style="color:var(--muted);grid-column:1/-1;text-align:center;padding:20px">شرکتی در این دسته یافت نشد.</p>
      @endforelse
    </div>
  </div>

  <!-- محتوای تب ۴: ماشین آلات -->
  <div class="showcase-content" id="tab-machinery">
    <div class="grid">
      @forelse($machineryCompanies as $company)
        @include('partials.card', ['company' => $company])
      @empty
        <p style="color:var(--muted);grid-column:1/-1;text-align:center;padding:20px">شرکتی در این دسته یافت نشد.</p>
      @endforelse
    </div>
  </div>
</section>

<!-- ۸. بخش ۱۲ قطب صنعتی برتر کشور (تفکیک جغرافیایی کارخانجات) -->
<section class="home-section">
  <div class="home-section-header">
    <div class="header-right">
      <h2 class="home-section-title">قطب‌های صنعتی برتر کشور</h2>
      <span class="home-section-tag">پوشش سراسری</span>
    </div>
    <span class="home-section-desc-inline">دسترسی مستقیم به کارخانجات بر اساس استان</span>
  </div>

  <div class="provinces-grid">
    @foreach($topProvinces as $province)
      <a href="{{ route('directory', ['province' => $province->id]) }}" class="province-card">
        <div class="province-card-right">
          <span class="province-pin">📍</span>
          <span class="province-name">{{ $province->name }}</span>
        </div>
        <span class="province-badge">{{ number_format($province->companies_count) }} واحد</span>
      </a>
    @endforeach
  </div>
</section>

<!-- ۹. بخش دانشنامه و مقالات تخصصی خریداران صنعت -->
<section class="home-section">
  <div class="home-section-header">
    <div class="header-right">
      <h2 class="home-section-title">دانشنامه و راهنمای تخصصی</h2>
      <span class="home-section-tag">راهنمای خریداران</span>
    </div>
  </div>

  <div class="knowledge-grid">
    
    <div class="knowledge-card">
      <div class="knowledge-tag">اصول فنی ورق‌سازی</div>
      <h3 class="knowledge-title">راهنمای محاسبه گراماژ و مقاومت فلوت در جعبه‌های صادراتی</h3>
      <p class="knowledge-desc">تفاوت مقاومت کارتن‌های فلوت B، C و ترکیب فلوت BC در برابر ضربه، ارتعاشات حمل‌ونقل و رطوبت سردخانه‌ها.</p>
      <div class="knowledge-link">
        <span>مطالعه راهنما</span>
        <span>←</span>
      </div>
    </div>

    <div class="knowledge-card">
      <div class="knowledge-tag">مواد اولیه کاغذ</div>
      <h3 class="knowledge-title">تفاوت کاغذ لاینر، کرافت خالص و فلوتینگ در استحکام کارتن</h3>
      <p class="knowledge-desc">چگونه با انتخاب ترکیب صحیح لایه‌های داخلی و خارجی، وزن بسته‌بندی را کاهش داده و استحکام جعبه را دوچندان کنیم؟</p>
      <div class="knowledge-link">
        <span>مطالعه راهنما</span>
        <span>←</span>
      </div>
    </div>

    <div class="knowledge-card">
      <div class="knowledge-tag">فناوری‌های چاپ</div>
      <h3 class="knowledge-title">انتخاب مناسب‌ترین شیوه چاپ: افست، فلکسو یا سیلک؟</h3>
      <p class="knowledge-desc">بررسی اقتصادی تیراژ، رزولوشن مورد نیاز، سلفون‌کشی و یووی موضعی در بسته‌بندی محصولات صنایع غذایی و دارویی.</p>
      <div class="knowledge-link">
        <span>مطالعه راهنما</span>
        <span>←</span>
      </div>
    </div>

  </div>
</section>

<!-- ۱۰. کادر فراخوان نصب اپلیکیشن و کانال تلگرام ۲ کیلو کاغذ -->
<section class="home-section" id="app-download">
  <div class="app-cta-banner">
    <div class="app-cta-content">
      <div class="app-cta-badge">همراه هوشمند صنعت</div>
      <h2 class="app-cta-title">اپلیکیشن اختصاصی ۲ کیلو کاغذ را نصب کنید</h2>
      <p class="app-cta-desc">بانک اطلاعات بیش از ۹۰۰ شرکت، استعلام لحظه‌ای قیمت، فرصت‌های شغلی و دسترسی آفلاین به تلفن کارخانجات همگی در جیب شما.</p>
      <div class="app-cta-actions">
        <a href="{{ route('home') }}" class="btn app-btn-white">
          <span>دانلود مستقیم اپلیکیشن</span>
          <span>📥</span>
        </a>
        <a href="https://t.me" target="_blank" rel="noopener" class="btn app-btn-trans">
          <span>عضویت در کانال تلگرام قیمت‌ها</span>
          <span>✈️</span>
        </a>
      </div>
    </div>
    <div class="app-cta-icon-box">
      <span style="font-size:100px;opacity:0.35">📱</span>
    </div>
  </div>
</section>

<!-- ۱۱. نوار آمار و اعتبار پلتفرم (۴ شاخص کلیدی اعتماد) -->
<section class="home-section">
  <div class="stats-counter-strip">
    <div class="stat-counter-item">
      <div class="stat-counter-num">۹۰۰+</div>
      <div class="stat-counter-label">کارخانه و تولیدکننده فعال</div>
    </div>
    <div class="stat-counter-item">
      <div class="stat-counter-num">۳۱</div>
      <div class="stat-counter-label">استان تحت پوشش سراسری</div>
    </div>
    <div class="stat-counter-item">
      <div class="stat-counter-num">۲۵۰+</div>
      <div class="stat-counter-label">رسته و کالای تخصصی کاغذ و چاپ</div>
    </div>
    <div class="stat-counter-item">
      <div class="stat-counter-num">۱۰۰٪</div>
      <div class="stat-counter-label">ارتباط مستقیم و بدون واسطه</div>
    </div>
  </div>
</section>

<style>
/* ==========================================================================
   استایل‌های اسلایدر تمام‌عرض ۵۰۰ پیکسلی (Center Mode Peek)
   ========================================================================== */
.home-slider-section {
  width: 100%;
  position: relative;
  overflow: hidden;
  margin: 0;
  padding: 16px 0 24px;
  background: #f1f5f9;
  border-bottom: 1px solid #e2e8f0;
}
.home-slider-wrapper {
  width: 100%;
  height: 500px;
  overflow: hidden;
  position: relative;
  margin: 0;
  padding: 0;
  direction: ltr;
}
.home-slider-track {
  display: flex;
  align-items: center;
  height: 500px;
  margin: 0;
  padding: 0;
  transition: transform 0.45s cubic-bezier(0.25, 1, 0.5, 1);
  will-change: transform;
}
.home-slider-item {
  flex-shrink: 0;
  height: 500px;
  box-sizing: border-box;
  transition: transform 0.45s ease, opacity 0.45s ease, filter 0.45s ease;
  user-select: none;
}
.home-slider-item.is-active {
  transform: scale(1);
  opacity: 1;
  filter: brightness(1);
  z-index: 5;
}
.home-slider-item.is-peek {
  transform: scale(0.92);
  opacity: 0.55;
  filter: brightness(0.85);
  cursor: pointer;
  z-index: 1;
}
.home-slider-item.is-peek:hover {
  opacity: 0.75;
}
.home-banner-card {
  width: 100%;
  height: 500px;
  position: relative;
  border-radius: 18px;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #ffffff;
  direction: rtl;
  text-align: right;
  margin: 0;
  padding: 0;
  box-shadow: 0 16px 36px -8px rgba(0, 0, 0, 0.28), 0 8px 16px -6px rgba(0, 0, 0, 0.15);
}
.home-banner-inner {
  width: 100%;
  height: 100%;
  padding: 48px 56px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  position: relative;
  z-index: 2;
  box-sizing: border-box;
}
.banner-decor {
  position: absolute;
  border-radius: 50%;
  pointer-events: none;
}
.decor-1 {
  width: 480px;
  height: 480px;
  background: radial-gradient(circle, rgba(255,255,255,0.18) 0%, rgba(255,255,255,0) 70%);
  top: -120px;
  left: -100px;
}
.decor-2 {
  width: 380px;
  height: 380px;
  background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, rgba(255,255,255,0) 70%);
  bottom: -90px;
  right: 15%;
}
.home-banner-content {
  position: relative;
  z-index: 2;
  max-width: 680px;
}
.home-banner-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.3);
  border-radius: 24px;
  padding: 6px 16px;
  font-size: 13.5px;
  font-weight: 700;
  color: #ffffff;
  margin-bottom: 20px;
}
.home-banner-title {
  margin: 0 0 16px;
  font-size: 30px;
  font-weight: 900;
  line-height: 1.35;
  color: #ffffff;
  letter-spacing: -0.5px;
}
.home-banner-desc {
  margin: 0 0 28px;
  font-size: 16px;
  color: rgba(255, 255, 255, 0.95);
  line-height: 1.8;
}
.home-banner-btn {
  background: #ffffff !important;
  font-weight: 800;
  font-size: 15px;
  padding: 12px 28px;
  border-radius: 10px;
  display: inline-flex;
  align-items: center;
  gap: 10px;
  text-decoration: none;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
  transition: transform 0.2s, box-shadow 0.2s;
}
.home-banner-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
}
.home-banner-graphic {
  position: relative;
  z-index: 1;
  font-size: 120px;
  opacity: 0.26;
  margin-right: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  pointer-events: none;
  user-select: none;
}
.home-slider-arrow {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.92);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  border: 1px solid rgba(0, 0, 0, 0.08);
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.22);
  color: #0f172a;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  z-index: 20;
  transition: all 0.2s ease;
  user-select: none;
}
.home-slider-arrow:hover {
  background: #ffffff;
  transform: translateY(-50%) scale(1.1);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.32);
  color: #0284c7;
}
.home-slider-prev { right: 20px; }
.home-slider-next { left: 20px; }
.home-slider-dots {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 8px;
  margin-top: 18px;
}
.home-slider-dot {
  width: 9px;
  height: 9px;
  border-radius: 5px;
  background: #cbd5e1;
  cursor: pointer;
  transition: all 0.3s ease;
}
.home-slider-dot.active {
  width: 28px;
  background: #0284c7;
}

/* ==========================================================================
   استایل‌های بخش‌های جدید صفحه اصلی
   ========================================================================== */
.home-section {
  margin-top: 48px;
}
.home-section-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 22px;
  flex-wrap: wrap;
  gap: 12px;
}
.header-right {
  display: flex;
  align-items: center;
  gap: 10px;
}
.home-section-title {
  margin: 0;
  font-size: 21px;
  font-weight: 900;
  color: #0f172a;
  letter-spacing: -0.3px;
}
.home-section-tag {
  font-size: 12px;
  font-weight: 700;
  padding: 3px 10px;
  border-radius: 20px;
  background: #eef2ff;
  color: #4338ca;
  border: 1px solid #e0e7ff;
}
.home-section-link {
  font-size: 13.5px;
  font-weight: 700;
  color: var(--primary);
  display: inline-flex;
  align-items: center;
  gap: 4px;
  text-decoration: none;
  transition: transform 0.2s;
}
.home-section-link:hover {
  transform: translateX(-4px);
}
.home-section-desc-inline {
  color: var(--muted);
  font-size: 13px;
}

/* ۱. بخش جستجو */
.home-search-section {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 18px;
  padding: 36px 32px 30px;
  margin-top: 24px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
}
.search-title-wrap {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 16px;
  margin-bottom: 8px;
}
.search-title-line {
  height: 1.5px;
  background: #e2e8f0;
  flex: 1;
  max-width: 140px;
}
.search-title-text {
  margin: 0;
  font-size: 24px;
  font-weight: 900;
  color: #0f172a;
  letter-spacing: -0.4px;
}
.search-subtitle {
  text-align: center;
  color: #64748b;
  font-size: 14.5px;
  margin: 0 auto 24px;
  max-width: 640px;
}
.home-search-bar {
  display: flex;
  gap: 10px;
  max-width: 980px;
  margin: 0 auto;
  flex-wrap: wrap;
}
.search-input-field {
  flex: 2.5;
  min-width: 250px;
  position: relative;
  display: flex;
  align-items: center;
}
.search-input-field input {
  width: 100%;
  padding: 13px 42px 13px 14px;
  border: 1.5px solid #cbd5e1;
  border-radius: 10px;
  font-size: 14.5px;
  font-family: inherit;
  transition: border-color 0.2s, box-shadow 0.2s;
}
.search-input-field input:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(31, 95, 168, 0.15);
}
.search-icon {
  position: absolute;
  right: 14px;
  font-size: 16px;
  pointer-events: none;
}
.search-select-field {
  flex: 1.2;
  min-width: 160px;
}
.search-select-field select {
  width: 100%;
  padding: 13px 14px 13px 36px !important;
  border: 1.5px solid #cbd5e1;
  border-radius: 10px;
  font-size: 14px;
  font-family: inherit;
  height: 100%;
}
.search-submit-btn {
  padding: 13px 32px;
  font-size: 15px;
  font-weight: 800;
  border-radius: 10px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 110px;
}
.search-chips-wrap {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  flex-wrap: wrap;
  margin-top: 20px;
  padding-top: 16px;
  border-top: 1px dashed #e2e8f0;
}
.search-chips-label {
  font-size: 13px;
  color: #64748b;
  font-weight: 600;
}
.search-chip {
  display: inline-block;
  padding: 4px 12px;
  background: #f1f5f9;
  color: #334155;
  border-radius: 16px;
  font-size: 12.5px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.2s;
}
.search-chip:hover {
  background: #e2e8f0;
  color: var(--primary);
  transform: translateY(-1px);
}

/* ۲. شبکه ۸ رسته اصلی */
.core-cat-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
}
.core-cat-card {
  display: flex;
  align-items: center;
  gap: 14px;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  padding: 16px;
  text-decoration: none;
  color: inherit;
  transition: all 0.25s ease;
  position: relative;
}
.core-cat-card:hover {
  border-color: #cbd5e1;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
  transform: translateY(-2px);
}
.core-cat-icon {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 22px;
  flex-shrink: 0;
}
.core-cat-info {
  flex: 1;
  min-width: 0;
}
.core-cat-name {
  margin: 0 0 4px;
  font-size: 14.5px;
  font-weight: 800;
  color: #0f172a;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.core-cat-count {
  font-size: 12px;
  color: #64748b;
}
.core-cat-arrow {
  font-size: 16px;
  font-weight: 700;
  opacity: 0.6;
  transition: transform 0.2s;
}
.core-cat-card:hover .core-cat-arrow {
  opacity: 1;
  transform: translateX(-4px);
}

/* ۳. سه بنر تبلیغاتی */
.triple-banners-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 18px;
}
.ad-promo-card {
  border-radius: 16px;
  padding: 26px 24px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  position: relative;
  overflow: hidden;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
  transition: transform 0.25s;
}
.ad-promo-card:hover {
  transform: translateY(-3px);
}
.promo-blue {
  background: linear-gradient(135deg, #1e3a8a 0%, #0369a1 100%);
  color: #ffffff;
}
.promo-green {
  background: linear-gradient(135deg, #064e3b 0%, #059669 100%);
  color: #ffffff;
}
.promo-amber {
  background: linear-gradient(135deg, #78350f 0%, #d97706 100%);
  color: #ffffff;
}
.promo-badge {
  display: inline-block;
  align-self: flex-start;
  font-size: 11.5px;
  font-weight: 800;
  padding: 3px 10px;
  border-radius: 12px;
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(4px);
  color: #ffffff;
  margin-bottom: 14px;
}
.promo-title {
  margin: 0 0 10px;
  font-size: 20px;
  font-weight: 900;
  color: #ffffff;
}
.promo-desc {
  margin: 0 0 20px;
  font-size: 13.5px;
  line-height: 1.7;
  color: rgba(255, 255, 255, 0.92);
}
.promo-action {
  margin-top: auto;
}
.promo-btn {
  background: #ffffff;
  color: #0f172a !important;
  font-weight: 800;
  font-size: 13px;
  padding: 8px 18px;
  border-radius: 8px;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  text-decoration: none;
  transition: background 0.2s;
}
.promo-btn:hover {
  background: #f8fafc;
}

/* نوار ترغیب مدیران به ویژه کردن شرکت */
.vip-cta-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
  border: 1.5px solid #fcd34d;
  border-radius: 14px;
  padding: 16px 24px;
  margin-top: 24px;
  flex-wrap: wrap;
  gap: 16px;
}
.vip-cta-text {
  display: flex;
  flex-direction: column;
  gap: 4px;
}
.vip-cta-text strong {
  font-size: 15px;
  color: #78350f;
}
.vip-cta-text span {
  font-size: 13px;
  color: #92400e;
}
.vip-cta-btn {
  background: #d97706 !important;
  color: #ffffff !important;
  font-weight: 800;
  padding: 10px 22px;
  border-radius: 8px;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}
.vip-cta-btn:hover {
  background: #b45309 !important;
}

/* ۴. بخش استخدام دو ستونه */
.jobs-dual-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}
.jobs-col {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  padding: 22px;
  display: flex;
  flex-direction: column;
}
.jobs-col-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding-bottom: 14px;
  border-bottom: 1.5px solid #f1f5f9;
  margin-bottom: 16px;
}
.jobs-list {
  display: flex;
  flex-direction: column;
  gap: 12px;
  flex: 1;
}
.job-item-card {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 14px;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  text-decoration: none;
  color: inherit;
  transition: all 0.2s;
}
.job-item-card:hover {
  background: #ffffff;
  border-color: var(--primary);
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
}
.job-item-main {
  min-width: 0;
  flex: 1;
}
.job-item-title {
  margin: 0 0 4px;
  font-size: 14px;
  font-weight: 700;
  color: #0f172a;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.job-item-company {
  margin: 0;
  font-size: 12px;
  color: #64748b;
}
.job-item-badges {
  display: flex;
  align-items: center;
  gap: 6px;
  margin-right: 12px;
  flex-shrink: 0;
}
.job-empty-box {
  padding: 24px;
  text-align: center;
  color: #94a3b8;
  font-size: 13.5px;
}
.jobs-col-footer {
  margin-top: 18px;
  padding-top: 12px;
  border-top: 1px solid #f1f5f9;
}

/* ۵. دو بنر میانی عریض */
.dual-banners-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}
.dual-banner-box {
  border-radius: 16px;
  padding: 32px 28px;
  position: relative;
  overflow: hidden;
  color: #ffffff;
}
.banner-shahbaz {
  background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
  border-right: 5px solid #38bdf8;
}
.banner-alum {
  background: linear-gradient(135deg, #14532d 0%, #166534 100%);
  border-right: 5px solid #4ade80;
}
.dual-banner-badge {
  display: inline-block;
  font-size: 11.5px;
  font-weight: 800;
  padding: 3px 10px;
  border-radius: 12px;
  background: rgba(255, 255, 255, 0.18);
  margin-bottom: 12px;
}
.dual-banner-title {
  margin: 0 0 10px;
  font-size: 22px;
  font-weight: 900;
  color: #ffffff;
}
.dual-banner-desc {
  margin: 0 0 22px;
  font-size: 13.5px;
  line-height: 1.7;
  color: rgba(255, 255, 255, 0.92);
}
.dual-banner-btn {
  background: #ffffff !important;
  color: #0f172a !important;
  font-weight: 800;
  font-size: 13px;
  padding: 9px 20px;
  border-radius: 8px;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

/* ۶. تب‌های نمایشگاه */
.showcase-tabs {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
  flex-wrap: wrap;
}
.showcase-tab {
  padding: 10px 20px;
  border-radius: 10px;
  background: #ffffff;
  border: 1.5px solid #e2e8f0;
  color: #334155;
  font-family: inherit;
  font-size: 14px;
  font-weight: 700;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: all 0.2s;
}
.showcase-tab:hover {
  border-color: #cbd5e1;
  background: #f8fafc;
}
.showcase-tab.active {
  background: var(--primary);
  border-color: var(--primary);
  color: #ffffff;
  box-shadow: 0 4px 12px rgba(31, 95, 168, 0.25);
}
.showcase-content {
  display: none;
  animation: fadeIn 0.3s ease;
}
.showcase-content.active {
  display: block;
}

/* ۷. شبکه ۱۲ استان برتر */
.provinces-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 14px;
}
.province-card {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 14px 18px;
  text-decoration: none;
  color: inherit;
  transition: all 0.2s;
}
.province-card:hover {
  border-color: var(--primary);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  transform: translateY(-2px);
}
.province-card-right {
  display: flex;
  align-items: center;
  gap: 8px;
}
.province-pin {
  font-size: 16px;
}
.province-name {
  font-size: 14px;
  font-weight: 700;
  color: #0f172a;
}
.province-badge {
  font-size: 12px;
  font-weight: 700;
  color: #64748b;
  background: #f1f5f9;
  padding: 2px 8px;
  border-radius: 10px;
}

/* ۸. دانشنامه و راهنماها */
.knowledge-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 18px;
}
.knowledge-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  padding: 24px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  transition: all 0.25s;
}
.knowledge-card:hover {
  border-color: #cbd5e1;
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
  transform: translateY(-3px);
}
.knowledge-tag {
  font-size: 12px;
  font-weight: 700;
  color: var(--primary);
  margin-bottom: 10px;
}
.knowledge-title {
  margin: 0 0 10px;
  font-size: 16px;
  font-weight: 800;
  color: #0f172a;
  line-height: 1.5;
}
.knowledge-desc {
  margin: 0 0 18px;
  font-size: 13px;
  line-height: 1.7;
  color: #64748b;
}
.knowledge-link {
  font-size: 13px;
  font-weight: 700;
  color: var(--primary);
  display: inline-flex;
  align-items: center;
  gap: 4px;
  margin-top: auto;
}

/* ۹. بنر دانلود اپلیکیشن */
.app-cta-banner {
  background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
  border-radius: 20px;
  padding: 44px 40px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  color: #ffffff;
  position: relative;
  overflow: hidden;
}
.app-cta-content {
  max-width: 600px;
  position: relative;
  z-index: 2;
}
.app-cta-badge {
  display: inline-block;
  background: rgba(255, 255, 255, 0.15);
  font-size: 12px;
  font-weight: 700;
  padding: 4px 12px;
  border-radius: 20px;
  margin-bottom: 14px;
}
.app-cta-title {
  margin: 0 0 12px;
  font-size: 26px;
  font-weight: 900;
  color: #ffffff;
}
.app-cta-desc {
  margin: 0 0 24px;
  font-size: 14.5px;
  line-height: 1.8;
  color: #cbd5e1;
}
.app-cta-actions {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}
.app-btn-white {
  background: #ffffff !important;
  color: #0f172a !important;
  font-weight: 800;
  padding: 11px 24px;
  border-radius: 10px;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}
.app-btn-trans {
  background: rgba(255, 255, 255, 0.15) !important;
  color: #ffffff !important;
  border: 1px solid rgba(255, 255, 255, 0.25);
  font-weight: 700;
  padding: 11px 22px;
  border-radius: 10px;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}
.app-btn-trans:hover {
  background: rgba(255, 255, 255, 0.25) !important;
}
.app-cta-icon-box {
  position: relative;
  z-index: 1;
}

/* ۱۰. نوار آمار */
.stats-counter-strip {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  padding: 28px 20px;
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
  text-align: center;
}
.stat-counter-item {
  position: relative;
}
.stat-counter-item:not(:last-child)::after {
  content: "";
  position: absolute;
  left: 0;
  top: 15%;
  height: 70%;
  width: 1px;
  background: #e2e8f0;
}
.stat-counter-num {
  font-size: 32px;
  font-weight: 900;
  color: var(--primary);
  margin-bottom: 6px;
  letter-spacing: -1px;
}
.stat-counter-label {
  font-size: 13.5px;
  color: #64748b;
  font-weight: 600;
}

/* ==========================================================================
   رسپانسیو و بهینه‌سازی موبایل و تبلت
   ========================================================================== */
@media (max-width: 1024px) {
  .core-cat-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  .triple-banners-grid {
    grid-template-columns: 1fr;
  }
  .provinces-grid {
    grid-template-columns: repeat(3, 1fr);
  }
  .knowledge-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .jobs-dual-grid {
    grid-template-columns: 1fr;
  }
  .dual-banners-grid {
    grid-template-columns: 1fr;
  }
  .provinces-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  .stats-counter-strip {
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
  }
  .stat-counter-item:not(:last-child)::after {
    display: none;
  }
  .home-search-section {
    padding: 24px 18px;
  }
  .search-input-field {
    flex: 1 1 100%;
  }
  .search-select-field {
    flex: 1 1 48%;
  }
  .search-submit-btn {
    flex: 1 1 100%;
  }
  .app-cta-banner {
    padding: 30px 24px;
  }
  .app-cta-icon-box {
    display: none;
  }
}

@media (max-width: 480px) {
  .core-cat-grid {
    grid-template-columns: 1fr;
  }
  .provinces-grid {
    grid-template-columns: 1fr;
  }
  .search-select-field {
    flex: 1 1 100%;
  }
}
</style>

<script>
/* اسکریپت تب‌های نمایشگاه */
function switchShowcaseTab(tabKey, btn) {
  document.querySelectorAll('.showcase-tab').forEach(b => b.classList.remove('active'));
  document.querySelectorAll('.showcase-content').forEach(c => c.classList.remove('active'));
  
  btn.classList.add('active');
  const target = document.getElementById('tab-' + tabKey);
  if (target) {
    target.classList.add('active');
  }
}

/* اسکریپت اسلایدر ۵۰۰ پیکسلی Center Mode Peek */
document.addEventListener('DOMContentLoaded', function () {
  const wrapper = document.getElementById('homeSliderWrapper');
  const track = document.getElementById('homeSliderTrack');
  const items = document.querySelectorAll('.home-slider-item');
  const dots = document.querySelectorAll('.home-slider-dot');
  const totalRealSlides = 5;
  
  if (!wrapper || !track || items.length === 0) return;

  let currentTrackIndex = 1;
  let isAnimating = false;
  let autoPlayTimer = null;

  function getSlideDimensions() {
    const wrapperW = wrapper.offsetWidth;
    const isMobile = window.innerWidth <= 768;
    const isSmallMobile = window.innerWidth <= 480;

    let slideRatio = 0.70;
    if (isSmallMobile) {
      slideRatio = 0.88;
    } else if (isMobile) {
      slideRatio = 0.80;
    }

    const slideW = Math.floor(wrapperW * slideRatio);
    const gap = isMobile ? 12 : 20;
    return { wrapperW, slideW, gap };
  }

  function applyDimensions() {
    const { slideW, gap } = getSlideDimensions();
    items.forEach((item, idx) => {
      item.style.width = slideW + 'px';
      if (idx < items.length - 1) {
        item.style.marginRight = gap + 'px';
      } else {
        item.style.marginRight = '0px';
      }
    });
  }

  function updatePosition(animate = true) {
    const { wrapperW, slideW, gap } = getSlideDimensions();
    const centerOffset = (wrapperW - slideW) / 2;
    const itemLeft = currentTrackIndex * (slideW + gap);
    const translateX = centerOffset - itemLeft;

    if (animate) {
      track.style.transition = 'transform 0.45s cubic-bezier(0.25, 1, 0.5, 1)';
    } else {
      track.style.transition = 'none';
    }

    track.style.transform = 'translateX(' + translateX + 'px)';

    let realIndex = 0;
    if (currentTrackIndex === 0) {
      realIndex = 4;
    } else if (currentTrackIndex === totalRealSlides + 1) {
      realIndex = 0;
    } else {
      realIndex = currentTrackIndex - 1;
    }

    items.forEach((item, idx) => {
      if (idx === currentTrackIndex) {
        item.classList.add('is-active');
        item.classList.remove('is-peek');
      } else {
        item.classList.remove('is-active');
        item.classList.add('is-peek');
      }
    });

    dots.forEach((dot, dotIdx) => {
      if (dotIdx === realIndex) {
        dot.classList.add('active');
      } else {
        dot.classList.remove('active');
      }
    });
  }

  function moveTo(index) {
    if (isAnimating) return;
    isAnimating = true;
    currentTrackIndex = index;
    updatePosition(true);
  }

  track.addEventListener('transitionend', function () {
    isAnimating = false;
    if (currentTrackIndex === 0) {
      currentTrackIndex = totalRealSlides;
      updatePosition(false);
    } else if (currentTrackIndex === totalRealSlides + 1) {
      currentTrackIndex = 1;
      updatePosition(false);
    }
  });

  window.moveHomeSlider = function (step) {
    stopAutoPlay();
    moveTo(currentTrackIndex + step);
    startAutoPlay();
  };

  window.goToHomeSlide = function (realIdx) {
    stopAutoPlay();
    moveTo(realIdx + 1);
    startAutoPlay();
  };

  items.forEach((item, idx) => {
    item.addEventListener('click', function (e) {
      if (idx !== currentTrackIndex) {
        if (e.target.tagName !== 'A' && !e.target.closest('a')) {
          e.preventDefault();
          stopAutoPlay();
          moveTo(idx);
          startAutoPlay();
        }
      }
    });
  });

  function startAutoPlay() {
    stopAutoPlay();
    autoPlayTimer = setInterval(function () {
      moveTo(currentTrackIndex + 1);
    }, 4500);
  }

  function stopAutoPlay() {
    if (autoPlayTimer) {
      clearInterval(autoPlayTimer);
      autoPlayTimer = null;
    }
  }

  wrapper.addEventListener('mouseenter', stopAutoPlay);
  wrapper.addEventListener('mouseleave', startAutoPlay);

  let touchStartX = 0;
  let touchEndX = 0;
  wrapper.addEventListener('touchstart', function (e) {
    stopAutoPlay();
    touchStartX = e.touches[0].clientX;
  }, { passive: true });

  wrapper.addEventListener('touchend', function (e) {
    touchEndX = e.changedTouches[0].clientX;
    const diff = touchEndX - touchStartX;
    if (Math.abs(diff) > 40) {
      if (diff > 0) {
        moveTo(currentTrackIndex - 1);
      } else {
        moveTo(currentTrackIndex + 1);
      }
    }
    startAutoPlay();
  });

  let resizeTimer;
  window.addEventListener('resize', function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function () {
      applyDimensions();
      updatePosition(false);
    }, 100);
  });

  applyDimensions();
  updatePosition(false);
  startAutoPlay();
});
</script>
@endsection
