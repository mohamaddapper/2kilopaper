<!doctype html>
<html lang="fa" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $title ?? 'دایرکتوری صنعت کاغذ، چاپ و بسته‌بندی' }} | {{ config('app.name') }}</title>
<meta name="description" content="{{ $description ?? 'دایرکتوری شرکت‌های کاغذ، چاپ و بسته‌بندی ایران' }}">
<meta name="robots" content="noindex, nofollow">
<link rel="canonical" href="{{ $canonical ?? url()->current() }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css"></noscript>
<link rel="stylesheet" href="{{ asset('assets/style.css') }}?v={{ file_exists(public_path('assets/style.css')) ? filemtime(public_path('assets/style.css')) : '1.2' }}">
<script>
  (function() {
    try {
      if (localStorage.getItem('theme') === 'dark') {
        document.documentElement.classList.add('dark');
      }
    } catch (e) {}
  })();
</script>
@stack('head')
</head>
<body>
<header class="site site-header-2row">
  <!-- ردیف اول: لوگو، جستجوی سراسری، تغییر شب/روز، ثبت اطلاعات و میز کار/ورود -->
  <div class="container header-top-row">
    <a class="logo header-brand" href="{{ route('home') }}">
      <span style="display:inline-flex;align-items:center;gap:8px">
        <span>{{ config('app.name') }}</span>
      </span>
    </a>

    <!-- جستجوی سریع سراسری در هدر -->
    <form class="header-search-form" action="{{ route('directory') }}" method="get" role="search">
      <input type="text" name="q" class="header-search-input" value="{{ request('q') }}" placeholder="جستجو برای نام شرکت، محصول یا رسته..." aria-label="جستجوی شرکت‌ها">
      <button type="submit" class="header-search-btn" aria-label="جستجو">
        {!! svg_icon('search', 17) !!}
      </button>
    </form>

    <!-- دکمه‌های کاربری سمت چپ -->
    <div class="header-user-actions">
      <!-- دکمه حالت شب و روز -->
      <button type="button" id="themeToggleBtn" class="theme-toggle-btn" onclick="toggleSiteTheme()" title="تغییر حالت شب و روز" aria-label="تغییر حالت شب و روز">
        <span class="theme-icon-sun" style="display:none">{!! svg_icon('sun', 17) !!}</span>
        <span class="theme-icon-moon">{!! svg_icon('moon', 17) !!}</span>
      </button>

      <!-- دکمه ثبت اطلاعات (منوی کشویی) -->
      <div class="header-dropdown header-add-dropdown">
        <button type="button" class="header-add-btn" onclick="toggleHeaderDropdown(this)">
          <span class="header-add-plus">{!! svg_icon('plus', 14) !!}</span>
          <span>ثبت اطلاعات</span>
          {!! svg_icon('chevron-down', 13) !!}
        </button>
        <div class="header-dropdown-menu">
          <a href="{{ route('companies.create') }}">{!! svg_icon('building', 15) !!} ثبت رایگان شرکت / کارخانه</a>
          <a href="{{ route('employer.jobs.create') }}">{!! svg_icon('briefcase', 15) !!} ثبت آگهی استخدام</a>
        </div>
      </div>

      <!-- دکمه حساب من / میز کار / ورود -->
      @auth
        <div class="header-dropdown">
          <button type="button" class="header-account-btn" onclick="toggleHeaderDropdown(this)">
            {!! svg_icon('user', 15) !!}
            <span>میز کار</span>
            {!! svg_icon('chevron-down', 13) !!}
          </button>
          <div class="header-dropdown-menu">
            <a href="{{ route('dashboard') }}">{!! svg_icon('user', 15) !!} میز کار من</a>
            @if(auth()->user()->isAdmin())
              <a href="{{ route('admin.dashboard') }}" style="color:#d97706;font-weight:700">{!! svg_icon('star-filled', 15) !!} پنل مدیریت</a>
            @endif
            <form method="post" action="{{ route('logout') }}" style="margin:0">
              @csrf
              <button type="submit" class="header-logout-item">{!! svg_icon('x-circle', 15) !!} خروج از حساب</button>
            </form>
          </div>
        </div>
      @else
        <a href="{{ route('login') }}" class="header-account-btn">
          {!! svg_icon('user', 15) !!}
          <span>ورود / ثبت‌نام</span>
        </a>
      @endauth

      <!-- دکمه منوی موبایل -->
      <button type="button" class="mobile-menu-toggle" onclick="toggleMobileHeaderNav()" aria-label="منوی سایت">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      </button>
    </div>
  </div>

  <!-- ردیف دوم: منوی اصلی سایت -->
  <nav class="header-bottom-row" id="headerBottomNav" aria-label="منوی اصلی">
    <div class="container header-nav-list">
      <a href="{{ route('home') }}" class="header-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
        {!! svg_icon('home', 16) !!}
        <span>خانه</span>
      </a>
      <span class="header-nav-sep"></span>

      <div class="header-dropdown">
        <a href="{{ route('directory') }}" class="header-nav-link {{ request()->routeIs('directory') ? 'active' : '' }}">
          {!! svg_icon('briefcase', 16) !!}
          <span>شرکت‌ها</span>
          {!! svg_icon('chevron-down', 13) !!}
        </a>
        <div class="header-dropdown-menu">
          <a href="{{ route('directory') }}">{!! svg_icon('building', 15) !!} همه شرکت‌ها و کارخانجات</a>
          <a href="{{ route('directory', ['featured' => 1]) }}">{!! svg_icon('star-filled', 15) !!} شرکت‌های ویژه</a>
          <a href="{{ route('companies.create') }}">{!! svg_icon('plus', 15) !!} ثبت رایگان شرکت</a>
        </div>
      </div>
      <span class="header-nav-sep"></span>

      <div class="header-dropdown">
        <a href="{{ route('categories.index') }}" class="header-nav-link {{ request()->routeIs('categories.*') || request()->routeIs('locations.*') ? 'active' : '' }}">
          {!! svg_icon('folder', 16) !!}
          <span>دسته‌بندی‌ها</span>
          {!! svg_icon('chevron-down', 13) !!}
        </a>
        <div class="header-dropdown-menu">
          <a href="{{ route('categories.index') }}">{!! svg_icon('folder', 15) !!} دسته‌بندی بر اساس فعالیت</a>
          <a href="{{ route('locations.index') }}">{!! svg_icon('map-pin', 15) !!} دسته‌بندی بر اساس استان</a>
          <a href="{{ route('categories.role', ['role' => 'تولیدکننده']) }}">{!! svg_icon('factory', 15) !!} تولیدکنندگان</a>
          <a href="{{ route('categories.role', ['role' => 'واردکننده']) }}">{!! svg_icon('ship', 15) !!} واردکنندگان</a>
          <a href="{{ route('categories.role', ['role' => 'فروشنده']) }}">{!! svg_icon('store', 15) !!} فروشندگان و تأمین‌کنندگان</a>
          <a href="{{ route('categories.role', ['role' => 'چاپخانه']) }}">{!! svg_icon('printer', 15) !!} چاپخانه‌ها</a>
          <a href="{{ route('categories.role', ['role' => 'خدمات‌دهنده']) }}">{!! svg_icon('settings', 15) !!} خدمات‌دهندگان فنی</a>
        </div>
      </div>
      <span class="header-nav-sep"></span>

      <div class="header-dropdown">
        <a href="{{ route('jobs.index') }}" class="header-nav-link {{ request()->routeIs('jobs.*') ? 'active' : '' }}">
          {!! svg_icon('users', 16) !!}
          <span>کاریابی</span>
          {!! svg_icon('chevron-down', 13) !!}
        </a>
        <div class="header-dropdown-menu">
          <a href="{{ route('jobs.index') }}">{!! svg_icon('briefcase', 15) !!} فرصت‌های شغلی و استخدام</a>
          <a href="{{ route('employer.jobs.create') }}">{!! svg_icon('plus', 15) !!} ثبت آگهی استخدام</a>
        </div>
      </div>
      <span class="header-nav-sep"></span>

      <div class="header-dropdown">
        <a href="{{ route('subscription.fee') }}" class="header-nav-link {{ request()->routeIs('subscription.fee') || request()->routeIs('advertising') ? 'active' : '' }}">
          {!! svg_icon('gem', 16) !!}
          <span>تعرفه‌ها</span>
          {!! svg_icon('chevron-down', 13) !!}
        </a>
        <div class="header-dropdown-menu">
          <a href="{{ route('subscription.fee') }}">{!! svg_icon('star-filled', 15) !!} تعرفه اشتراک ویژه</a>
          <a href="{{ route('advertising') }}">{!! svg_icon('megaphone', 15) !!} تعرفه تبلیغات و بنرها</a>
        </div>
      </div>
      <span class="header-nav-sep"></span>

      <div class="header-dropdown">
        <a href="{{ route('pages.about') }}" class="header-nav-link {{ request()->routeIs('pages.*') ? 'active' : '' }}">
          {!! svg_icon('info', 16) !!}
          <span>سایر اطلاعات</span>
          {!! svg_icon('chevron-down', 13) !!}
        </a>
        <div class="header-dropdown-menu">
          <a href="{{ route('pages.phone-numbers') }}">{!! svg_icon('phone', 15) !!} شماره تماس‌های ضروری صنایع سلولزی</a>
          <a href="{{ route('pages.app') }}">{!! svg_icon('smartphone', 15) !!} دانلود اپلیکیشن</a>
          <a href="{{ route('pages.faqs') }}">{!! svg_icon('help', 15) !!} سوالات متداول کاربران</a>
          <a href="{{ route('pages.about') }}">{!! svg_icon('info', 15) !!} درباره ۲ کیلو کاغذ</a>
          <a href="{{ route('pages.contact') }}">{!! svg_icon('mail', 15) !!} تماس با ما</a>
        </div>
      </div>
    </div>
  </nav>
</header>
@yield('hero')
<main class="container">
  @if(session('ok'))<div class="card" style="border-color:#2e9b5b;margin-bottom:16px">{{ session('ok') }}</div>@endif
  @yield('content')
</main>
<footer class="site-footer">
  <div class="container footer-container">
    
    <div class="footer-grid">
      <!-- ستون ۱: درباره ۲ کیلو کاغذ -->
      <div class="footer-col col-about">
        <h3 class="footer-col-title">درباره ۲ کیلو کاغذ</h3>
        <p class="footer-about-text">
          مرکز و مرجع جامع صنعت کاغذ، کارتن، مقوا، چاپ و بسته‌بندی ایران؛ بستری هوشمند برای ارتباط مستقیم و بی‌واسطه تولیدکنندگان، واردکنندگان، چاپخانه‌ها و خریداران سراسر کشور.
        </p>
        <div class="footer-social-row">
          <a href="https://ble.ir/dokilopaper" target="_blank" rel="noopener" class="footer-social-btn" title="پیام‌رسان بله">
            <span>{!! svg_icon('message', 15) !!} بله</span>
          </a>
          <a href="https://t.me/dokilopaper" target="_blank" rel="noopener" class="footer-social-btn" title="تلگرام">
            <span>{!! svg_icon('send', 15) !!} تلگرام</span>
          </a>
          <a href="https://instagram.com/2kilopaper" target="_blank" rel="noopener" class="footer-social-btn" title="اینستاگرام">
            <span>{!! svg_icon('camera', 15) !!} اینستاگرام</span>
          </a>
        </div>
      </div>

      <!-- ستون ۲: دسترسی سریع و خدمات -->
      <div class="footer-col">
        <h4 class="footer-col-title">دسترسی سریع</h4>
        <ul class="footer-links">
          <li><a href="{{ route('home') }}">صفحه اصلی</a></li>
          <li><a href="{{ route('directory') }}">دایرکتوری و جستجوی شرکت‌ها</a></li>
          <li><a href="{{ route('categories.index') }}">دسته‌بندی بر اساس زمینه فعالیت</a></li>
          <li><a href="{{ route('locations.index') }}">دسته‌بندی بر اساس استان</a></li>
          <li><a href="{{ route('jobs.index') }}">فرصت‌های شغلی و کاریابی</a></li>
          <li><a href="{{ route('subscription.fee') }}">تعرفه‌های اشتراک طلایی</a></li>
          <li><a href="{{ route('advertising') }}">تعرفه و جایگاه‌های تبلیغات</a></li>
          <li><a href="{{ route('companies.create') }}">ثبت رایگان شرکت و کارگاه</a></li>
        </ul>
      </div>

      <!-- ستون ۳: راهنما و صفحات رسمی -->
      <div class="footer-col">
        <h4 class="footer-col-title">راهنما و اطلاعات</h4>
        <ul class="footer-links">
          <li><a href="{{ route('pages.phone-numbers') }}">شماره تماس‌های صنایع سلولزی</a></li>
          <li><a href="{{ route('pages.app') }}">دانلود اپلیکیشن ۲ کیلو کاغذ</a></li>
          <li><a href="{{ route('pages.faqs') }}">سوالات متداول کاربران</a></li>
          <li><a href="{{ route('pages.about') }}">درباره ۲ کیلو کاغذ</a></li>
          <li><a href="{{ route('pages.terms') }}">شرایط و قوانین استفاده</a></li>
          <li><a href="{{ route('pages.privacy') }}">سیاست‌نامه حریم خصوصی</a></li>
        </ul>
      </div>

      <!-- ستون ۴: ارتباط با پشتیبانی -->
      <div class="footer-col col-contact">
        <h4 class="footer-col-title">تماس و پشتیبانی</h4>
        <div class="footer-contact-item">
          <span class="f-icon">{!! svg_icon('phone', 15) !!}</span>
          <div class="f-info">
            <span class="f-lbl">پشتیبانی تلفنی:</span>
            <a href="tel:02128421075" class="f-val ltr-text">۰۲۱-۲۸۴۲۱۰۷۵</a>
          </div>
        </div>

        <div class="footer-contact-item">
          <span class="f-icon">{!! svg_icon('mail', 15) !!}</span>
          <div class="f-info">
            <span class="f-lbl">پست الکترونیکی:</span>
            <a href="mailto:support@2kilopaper.ir" class="f-val ltr-text">support@2kilopaper.ir</a>
          </div>
        </div>

        <div class="footer-contact-item">
          <span class="f-icon">{!! svg_icon('map-pin', 15) !!}</span>
          <div class="f-info">
            <span class="f-lbl">آدرس دفتر مرکزی:</span>
            <span class="f-val" style="font-size:12.5px">تهران، خیابان مطهری، خیابان میرعماد، پلاک ۱۱، طبقه ۲</span>
          </div>
        </div>
      </div>
    </div>

    <!-- نوار کپی‌رایت پایین -->
    <div class="footer-bottom-bar">
      <div class="footer-copyright">
        © {{ date('Y') }} ۲ کیلو کاغذ · کلیه حقوق مادی و معنوی محفوظ است.
      </div>
      <div class="footer-bottom-links">
        <a href="{{ route('pages.terms') }}">قوانین و ضوابط</a>
        <span>·</span>
        <a href="{{ route('pages.privacy') }}">حریم خصوصی</a>
        <span>·</span>
        <a href="{{ route('pages.contact') }}">تماس با ما</a>
      </div>
    </div>

  </div>
</footer>

<style>
.site-footer {
  background: #ffffff;
  border-top: 1px solid var(--border);
  margin-top: 48px;
  padding: 44px 0 24px;
  color: var(--text);
  font-size: 13.5px;
}
.footer-grid {
  display: grid;
  grid-template-columns: 1.4fr 1fr 1fr 1.2fr;
  gap: 32px;
  margin-bottom: 36px;
}
.footer-col-title {
  margin: 0 0 16px;
  font-size: 15.5px;
  font-weight: 900;
  color: #0f172a;
  position: relative;
  padding-bottom: 8px;
}
.footer-col-title::after {
  content: "";
  position: absolute;
  bottom: 0;
  right: 0;
  width: 28px;
  height: 2px;
  background: var(--primary);
  border-radius: 2px;
}
.footer-about-text {
  color: #64748b;
  line-height: 1.8;
  margin: 0 0 16px;
  font-size: 13px;
}
.footer-social-row {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}
.footer-social-btn {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 5px 12px;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  color: #475569;
  font-size: 12px;
  font-weight: 700;
  text-decoration: none;
  transition: all 0.2s;
}
.footer-social-btn:hover {
  background: var(--primary);
  color: #ffffff;
  border-color: var(--primary);
}

.footer-links {
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
  gap: 10px;
}
.footer-links a {
  color: #64748b;
  text-decoration: none;
  font-size: 13px;
  transition: all 0.2s ease;
  display: inline-block;
}
.footer-links a:hover {
  color: var(--primary);
  transform: translateX(-4px);
}

.footer-contact-item {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  margin-bottom: 12px;
}
.f-icon {
  font-size: 16px;
  flex-shrink: 0;
  margin-top: 2px;
}
.f-info {
  display: flex;
  flex-direction: column;
}
.f-lbl {
  font-size: 11px;
  color: #94a3b8;
  font-weight: 600;
}
.f-val {
  color: #1e293b;
  font-weight: 700;
  font-size: 13.5px;
  text-decoration: none;
}
.f-val:hover {
  color: var(--primary);
}
.ltr-text {
  direction: ltr;
  text-align: right;
}

.footer-bottom-bar {
  padding-top: 20px;
  border-top: 1px solid #f1f5f9;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  flex-wrap: wrap;
  font-size: 12.5px;
  color: #94a3b8;
}
.footer-bottom-links {
  display: flex;
  align-items: center;
  gap: 8px;
}
.footer-bottom-links a {
  color: #64748b;
  text-decoration: none;
  transition: color 0.2s;
}
.footer-bottom-links a:hover {
  color: var(--primary);
}

@media (max-width: 992px) {
  .footer-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 28px;
  }
}
@media (max-width: 580px) {
  .footer-grid {
    grid-template-columns: 1fr;
    gap: 24px;
  }
  .footer-bottom-bar {
    flex-direction: column;
    text-align: center;
    gap: 10px;
  }
}

/* ==========================================================================
   استایل هدر ۲ ردیفه + جستجوی سراسری + تغییر حالت شب و روز
   ========================================================================== */
.site-header-2row {
  background: var(--card);
  border-bottom: 1px solid var(--border);
  position: sticky;
  top: 0;
  z-index: 90;
  box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
}
.header-top-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 18px;
  padding-top: 11px;
  padding-bottom: 11px;
}
.header-brand {
  font-size: 21px;
  font-weight: 900;
  color: var(--primary);
  white-space: nowrap;
  flex-shrink: 0;
}
.header-search-form {
  flex: 1;
  max-width: 440px;
  position: relative;
  display: flex;
  align-items: center;
  margin: 0;
}
.header-search-input {
  width: 100%;
  padding: 9px 14px 9px 40px !important;
  border: 1.5px solid var(--border) !important;
  border-radius: 10px !important;
  background: var(--bg) !important;
  color: var(--text) !important;
  font-size: 13.5px !important;
  font-family: inherit;
  transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
}
.header-search-input:focus {
  outline: none;
  border-color: var(--primary) !important;
  background: var(--card) !important;
  box-shadow: 0 0 0 3px rgba(31, 95, 168, 0.12);
}
.header-search-btn {
  position: absolute;
  left: 8px;
  top: 50%;
  transform: translateY(-50%);
  width: 30px;
  height: 30px;
  border: none;
  background: transparent;
  color: var(--muted);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  border-radius: 8px;
  transition: color 0.15s, background 0.15s;
}
.header-search-btn:hover {
  color: var(--primary);
  background: rgba(31, 95, 168, 0.08);
}
.header-user-actions {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-shrink: 0;
}
.theme-toggle-btn {
  width: 38px;
  height: 38px;
  border-radius: 10px;
  border: 1px solid var(--border);
  background: var(--bg);
  color: var(--text);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s;
}
.theme-toggle-btn:hover {
  border-color: var(--primary);
  color: var(--primary);
}
html.dark .theme-icon-sun { display: inline-flex !important; color: #fbbf24; }
html.dark .theme-icon-moon { display: none !important; }
.header-add-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 7px 13px;
  border-radius: 10px;
  border: 1px solid var(--border);
  background: transparent;
  color: var(--text);
  font-size: 13.5px;
  font-weight: 700;
  font-family: inherit;
  cursor: pointer;
  white-space: nowrap;
  transition: all 0.15s;
}
.header-add-btn:hover {
  border-color: var(--primary);
  color: var(--primary);
}
.header-add-plus {
  width: 20px;
  height: 20px;
  border-radius: 50%;
  border: 1.5px solid currentColor;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}
.header-account-btn {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 8px 16px;
  border-radius: 10px;
  border: none;
  background: var(--primary);
  color: #fff !important;
  font-size: 13.5px;
  font-weight: 700;
  font-family: inherit;
  cursor: pointer;
  text-decoration: none;
  white-space: nowrap;
  transition: background 0.15s;
}
.header-account-btn:hover {
  background: var(--primary-d);
}
.header-bottom-row {
  border-top: 1px solid var(--border);
  background: var(--card);
}
.header-nav-list {
  display: flex;
  align-items: center;
  gap: 4px;
  padding-top: 6px;
  padding-bottom: 6px;
  flex-wrap: wrap;
}
.header-nav-link {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 7px 11px;
  border-radius: 8px;
  color: var(--text);
  font-size: 13.5px;
  font-weight: 600;
  text-decoration: none;
  white-space: nowrap;
  transition: background 0.15s, color 0.15s;
}
.header-nav-link:hover,
.header-nav-link.active {
  background: rgba(31, 95, 168, 0.08);
  color: var(--primary);
}
.header-nav-sep {
  width: 1px;
  height: 16px;
  background: var(--border);
  margin: 0 2px;
}
.header-dropdown {
  position: relative;
}
.header-dropdown-menu {
  position: absolute;
  top: calc(100% + 6px);
  right: 0;
  min-width: 220px;
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 6px;
  box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);
  display: none;
  flex-direction: column;
  gap: 2px;
  z-index: 200;
}
.header-dropdown-menu::before {
  content: "";
  position: absolute;
  top: -12px;
  left: 0;
  right: 0;
  height: 12px;
}
.header-user-actions .header-dropdown-menu {
  right: auto;
  left: 0;
}
.header-dropdown.open > .header-dropdown-menu {
  display: flex;
}
@media (min-width: 961px) {
  .header-bottom-row .header-dropdown:hover > .header-dropdown-menu {
    display: flex;
  }
}
.header-dropdown-menu a,
.header-logout-item {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 12px;
  border-radius: 8px;
  color: var(--text);
  font-size: 13px;
  font-weight: 600;
  text-decoration: none;
  border: none;
  background: transparent;
  width: 100%;
  text-align: right;
  font-family: inherit;
  cursor: pointer;
  transition: background 0.15s, color 0.15s;
}
.header-dropdown-menu a:hover,
.header-logout-item:hover {
  background: rgba(31, 95, 168, 0.08);
  color: var(--primary);
}
.header-logout-item {
  color: #dc2626;
}
.header-logout-item:hover {
  background: rgba(220, 38, 38, 0.08);
  color: #dc2626;
}
.mobile-menu-toggle {
  display: none;
  width: 38px;
  height: 38px;
  border-radius: 10px;
  border: 1px solid var(--border);
  background: var(--bg);
  color: var(--text);
  align-items: center;
  justify-content: center;
  cursor: pointer;
}
@media (max-width: 960px) {
  .header-top-row {
    flex-wrap: wrap;
    gap: 10px;
  }
  .header-search-form {
    order: 3;
    flex: 1 1 100%;
    max-width: 100%;
  }
  .header-add-dropdown {
    display: none;
  }
  .mobile-menu-toggle {
    display: inline-flex;
  }
  .header-bottom-row {
    display: none;
  }
  .header-bottom-row.mobile-open {
    display: block;
  }
  .header-nav-list {
    flex-direction: column;
    align-items: stretch;
    gap: 4px;
    padding-top: 10px;
    padding-bottom: 12px;
  }
  .header-nav-sep {
    display: none;
  }
  .header-dropdown-menu {
    position: static;
    box-shadow: none;
    border: none;
    padding-right: 16px;
    background: transparent;
  }
}
</style>

<script>
function toggleSiteTheme() {
  const isDark = document.documentElement.classList.toggle('dark');
  try {
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
  } catch (e) {}
}

function toggleHeaderDropdown(btn) {
  const parent = btn.closest('.header-dropdown');
  const wasOpen = parent.classList.contains('open');
  document.querySelectorAll('.header-dropdown.open').forEach(el => el.classList.remove('open'));
  if (!wasOpen) parent.classList.add('open');
}

function toggleMobileHeaderNav() {
  const nav = document.getElementById('headerBottomNav');
  if (nav) nav.classList.toggle('mobile-open');
}

document.addEventListener('click', function(e) {
  if (!e.target.closest('.header-dropdown')) {
    document.querySelectorAll('.header-dropdown.open').forEach(el => el.classList.remove('open'));
  }
});
</script>
</body>
</html>
