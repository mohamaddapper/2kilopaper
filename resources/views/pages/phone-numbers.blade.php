@extends('layouts.app', [
  'title' => 'شماره تماس‌های ضروری صنایع سلولزی',
  'description' => 'شماره تماس و آدرس واحدهای وابسته به صنایع سلولزی شامل کاغذ، کارتن، مقوا، چاپ و نوشت‌افزار در ایران'
])

@section('content')
<div class="page-container">

  <!-- هدر صفحه و معرفی -->
  <div class="page-hero-card">
    <div class="page-hero-badge">
      <span>📞</span>
      <span>اطلاعات تماس ارگان‌ها و تشکل‌ها</span>
    </div>
    <h1 class="page-hero-title">شماره تماس‌های ضروری صنایع سلولزی</h1>
    <p class="page-hero-desc">
      در این فهرست شماره تماس‌ و آدرس‌ واحدهای دولتی، سندیکاها، انجمن‌ها و تعاونی‌های وابسته به صنایع سلولزی کشور (کاغذ، کارتن، مقوا، چاپ، نوشت‌افزار و بهداشتی) گردآوری شده است تا بتوانید به سرعت با آن‌ها ارتباط برقرار فرمایید.
    </p>
    <div class="page-hero-update-pill">
      <span>🔄</span>
      <span>این فهرست به‌مرور و به صورت مستمر توسط ۲ کیلو کاغذ به‌روزرسانی می‌شود.</span>
    </div>
  </div>

  <!-- کادر جستجوی سریع در تشکل‌ها -->
  <div class="quick-filter-wrap">
    <div class="search-input-box">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
      <input type="text" id="phoneSearchInput" placeholder="جستجو در نام ارگان، آدرس یا شماره تماس..." oninput="filterPhoneCards()">
    </div>
  </div>

  <!-- شبکه کارت‌های اطلاعات تماس تشکل‌ها -->
  <div class="org-cards-grid" id="orgCardsGrid">

    <!-- کارت ۱: وزارت صمت -->
    <div class="org-card" data-search="دفتر صنایع سلولزی چاپ و نوشت افزار وزارت صنعت معدن تجارت ویلا کلانتری 02181762292 02181762270 02181761 02188898077">
      <div class="org-card-header">
        <span class="org-badge badge-gov">وزارت صنعت، معدن و تجارت</span>
        <h2 class="org-title">دفتر صنایع سلولزی، چاپ و نوشت‌افزار</h2>
      </div>
      <div class="org-body">
        <div class="org-address-row">
          <span class="icon">📍</span>
          <p class="address-text">تهران، خیابان کریمخان زند، خیابان استاد نجات‌الهی (ویلا)، خیابان کلانتری، وزارت صنعت، معدن و تجارت (ساختمان شهید حسین الهی)</p>
          <button type="button" class="copy-btn" onclick="copyAddress(this, 'تهران، خیابان کریمخان زند، خیابان استاد نجات‌الهی (ویلا)، خیابان کلانتری، وزارت صمت (ساختمان شهید حسین الهی)')" title="کپی آدرس">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
          </button>
        </div>

        <div class="org-phones-section">
          <span class="phones-title">شماره‌های مستقیم دفتر:</span>
          <div class="phones-buttons-row">
            <a href="tel:02181762292" class="call-btn primary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
              <span>۰۲۱-۸۱۷۶۲۲۹۲</span>
            </a>
            <a href="tel:02181762270" class="call-btn primary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
              <span>۰۲۱-۸۱۷۶۲۲۷۰</span>
            </a>
          </div>
        </div>

        <div class="org-notice-box">
          <span class="notice-icon">ℹ️</span>
          <p>در صورت عدم پاسخگویی خطوط مستقیم، با شماره‌های سانترال زیر تماس گرفته و داخلی صنایع سلولزی را درخواست فرمایید:</p>
        </div>

        <div class="phones-buttons-row">
          <a href="tel:02181761" class="call-btn outline">
            <span>سانترال: ۰۲۱-۸۱۷۶۱</span>
          </a>
          <a href="tel:02188898077" class="call-btn outline">
            <span>۰۲۱-۸۸۸۹۸۰۷۷</span>
          </a>
        </div>
      </div>
    </div>

    <!-- کارت ۲: انجمن واردکنندگان -->
    <div class="org-card" data-search="انجمن واردکنندگان کاغذ مقوا و فرآورده های سلولزی ایران مطهری مفتح شمالی اصلی پور 02186073504 02188328065">
      <div class="org-card-header">
        <span class="org-badge badge-import">انجمن صنفی بازرگانی</span>
        <h2 class="org-title">انجمن واردکنندگان کاغذ، مقوا و فرآورده‌های سلولزی ایران</h2>
      </div>
      <div class="org-body">
        <div class="org-address-row">
          <span class="icon">📍</span>
          <p class="address-text">تهران، خیابان مطهری، خیابان مفتح شمالی، کوچه اصلی‌پور، پلاک ۳۶، طبقه اول، واحد ۱</p>
          <button type="button" class="copy-btn" onclick="copyAddress(this, 'تهران، خیابان مطهری، خیابان مفتح شمالی، کوچه اصلی‌پور، پلاک ۳۶، طبقه اول، واحد ۱')" title="کپی آدرس">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
          </button>
        </div>

        <div class="org-phones-section">
          <span class="phones-title">شماره‌های تماس دبیرخانه انجمن:</span>
          <div class="phones-buttons-row">
            <a href="tel:02186073504" class="call-btn primary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
              <span>۰۲۱-۸۶۰۷۳۵۰۴</span>
            </a>
            <a href="tel:02188328065" class="call-btn primary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
              <span>۰۲۱-۸۸۳۲۸۰۶۵</span>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- کارت ۳: سندیکای تولیدکنندگان -->
    <div class="org-card" data-search="سندیکای تولیدکنندگان کاغذ و مقوای ایران یوسف آباد اسدآبادی ساختمان نگین 02188607899 02188042516">
      <div class="org-card-header">
        <span class="org-badge badge-prod">سندیکای سراسری تولید</span>
        <h2 class="org-title">سندیکای تولیدکنندگان کاغذ و مقوای ایران</h2>
      </div>
      <div class="org-body">
        <div class="org-address-row">
          <span class="icon">📍</span>
          <p class="address-text">تهران، خیابان سید جمال‌الدین اسدآبادی (یوسف‌آباد)، نبش خیابان پنجاه و هشتم، شماره ۴۲۲، ساختمان نگین، طبقه اول، واحد ۴</p>
          <button type="button" class="copy-btn" onclick="copyAddress(this, 'تهران، خیابان یوسف‌آباد، نبش خیابان ۵۸، شماره ۴۲۲، ساختمان نگین، طبقه اول، واحد ۴')" title="کپی آدرس">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
          </button>
        </div>

        <div class="org-phones-section">
          <span class="phones-title">شماره‌های تماس دبیرخانه سندیکا:</span>
          <div class="phones-buttons-row">
            <a href="tel:02188607899" class="call-btn primary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
              <span>۰۲۱-۸۸۶۰۷۸۹۹</span>
            </a>
            <a href="tel:02188042516" class="call-btn primary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
              <span>۰۲۱-۸۸۰۴۲۵۱۶</span>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- کارت ۴: انجمن صنایع سلولزی بهداشتی -->
    <div class="org-card" data-search="انجمن صنایع سلولزی بهداشتی ایران سندیکای صنایع کاغذی و بهداشتی یوسف آباد اسدآبادی 02188721175 02188721848 02188711951 02188711864">
      <div class="org-card-header">
        <span class="org-badge badge-health">صنایع بهداشتی و تیشو</span>
        <h2 class="org-title">انجمن صنایع سلولزی بهداشتی ایران</h2>
        <span class="org-subtitle-inline">(سندیکای صنایع کاغذی و بهداشتی ایران)</span>
      </div>
      <div class="org-body">
        <div class="org-address-row">
          <span class="icon">📍</span>
          <p class="address-text">تهران، خیابان سید جمال‌الدین اسدآبادی (یوسف‌آباد)، بین خیابان ۲۰ و ۲۲، شماره ۱۹۲، طبقه سوم</p>
          <button type="button" class="copy-btn" onclick="copyAddress(this, 'تهران، خیابان یوسف‌آباد، بین خیابان ۲۰ و ۲۲، شماره ۱۹۲، طبقه سوم')" title="کپی آدرس">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
          </button>
        </div>

        <div class="org-phones-section">
          <span class="phones-title">شماره‌های تماس انجمن:</span>
          <div class="phones-buttons-row">
            <a href="tel:02188721175" class="call-btn primary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
              <span>۰۲۱-۸۸۷۲۱۱۷۵</span>
            </a>
            <a href="tel:02188721848" class="call-btn primary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
              <span>۰۲۱-۸۸۷۲۱۸۴۸</span>
            </a>
            <a href="tel:02188711951" class="call-btn primary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
              <span>۰۲۱-۸۸۷۱۱۹۵۱</span>
            </a>
            <a href="tel:02188711864" class="call-btn primary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
              <span>۰۲۱-۸۸۷۱۱۸۶۴</span>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- کارت ۵: شرکت تعاونی کارتن و ورق -->
    <div class="org-card" data-search="شرکت تعاونی اعضای انجمن مدیران صنایع کارتن و ورق یادگار امام تیموری آزادی 02166066822 02166066823">
      <div class="org-card-header">
        <span class="org-badge badge-carton">صنایع کارتن و بسته‌بندی</span>
        <h2 class="org-title">شرکت تعاونی اعضای انجمن مدیران صنایع کارتن و ورق</h2>
      </div>
      <div class="org-body">
        <div class="org-address-row">
          <span class="icon">📍</span>
          <p class="address-text">تهران، بزرگراه یادگار امام (ره) به طرف آزادی، خیابان شهید تیموری، پلاک ۸۱</p>
          <button type="button" class="copy-btn" onclick="copyAddress(this, 'تهران، بزرگراه یادگار امام به طرف آزادی، خیابان شهید تیموری، پلاک ۸۱')" title="کپی آدرس">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
          </button>
        </div>

        <div class="org-phones-section">
          <span class="phones-title">شماره‌های تماس دفتر تعاونی:</span>
          <div class="phones-buttons-row">
            <a href="tel:02166066822" class="call-btn primary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
              <span>۰۲۱-۶۶۰۶۶۸۲۲</span>
            </a>
            <a href="tel:02166066823" class="call-btn primary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
              <span>۰۲۱-۶۶۰۶۶۸۲۳</span>
            </a>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- راهنمای ثبت شماره تشکل جدید -->
  <div class="submit-org-cta">
    <div class="cta-text">
      <strong>شما هم مدیر یا نماینده یکی از انجمن‌ها و اتحادیه‌های صنعت هستید؟</strong>
      <span>برای افزودن شماره یا اصلاح اطلاعات تشکل خود، با تیم پشتیبانی ۲ کیلو کاغذ تماس بگیرید.</span>
    </div>
    <a href="{{ route('pages.contact') }}" class="btn cta-btn">
      <span>تماس با پشتیبانی</span>
      <span>←</span>
    </a>
  </div>

</div>

<!-- پیام کوچک Toast کپی آدرس -->
<div id="copyToast" class="copy-toast">آدرس در کلیپ‌بورد کپی شد</div>

<style>
.page-container {
  max-width: 1040px;
  margin: 32px auto 60px;
  padding: 0 16px;
}
.page-hero-card {
  background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #0284c7 100%);
  color: #ffffff;
  border-radius: 20px;
  padding: 40px 36px 34px;
  text-align: center;
  position: relative;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(30, 58, 138, 0.16);
  margin-bottom: 28px;
}
.page-hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 5px 14px;
  background: rgba(255, 255, 255, 0.18);
  backdrop-filter: blur(10px);
  border-radius: 30px;
  font-size: 13px;
  font-weight: 700;
  margin-bottom: 16px;
  border: 1px solid rgba(255, 255, 255, 0.25);
}
.page-hero-title {
  margin: 0 0 12px;
  font-size: 27px;
  font-weight: 900;
  letter-spacing: -0.4px;
  line-height: 1.35;
}
.page-hero-desc {
  max-width: 760px;
  margin: 0 auto 20px;
  font-size: 14.5px;
  line-height: 1.8;
  color: #e0f2fe;
}
.page-hero-update-pill {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 6px 16px;
  background: rgba(0, 0, 0, 0.22);
  border-radius: 12px;
  font-size: 12.5px;
  color: #fef08a;
}

.quick-filter-wrap {
  margin-bottom: 24px;
}
.search-input-box {
  display: flex;
  align-items: center;
  gap: 12px;
  background: #ffffff;
  border: 1.5px solid #cbd5e1;
  border-radius: 14px;
  padding: 12px 18px;
  box-shadow: 0 3px 12px rgba(0, 0, 0, 0.04);
  color: #64748b;
  transition: all 0.2s ease;
}
.search-input-box:focus-within {
  border-color: #2563eb;
  box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
  color: #2563eb;
}
.search-input-box input {
  flex: 1;
  border: none;
  outline: none;
  font-size: 14.5px;
  font-family: inherit;
  color: #0f172a;
  background: transparent;
}

.org-cards-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 20px;
  margin-bottom: 36px;
}
.org-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
  transition: all 0.25s ease;
  display: flex;
  flex-direction: column;
}
.org-card:hover {
  transform: translateY(-3px);
  border-color: #cbd5e1;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
}
.org-card-header {
  padding: 20px 20px 14px;
  border-bottom: 1px solid #f1f5f9;
  background: #f8fafc;
}
.org-badge {
  display: inline-block;
  font-size: 11.5px;
  font-weight: 700;
  padding: 3px 10px;
  border-radius: 8px;
  margin-bottom: 8px;
}
.badge-gov { background: #fee2e2; color: #991b1b; }
.badge-import { background: #e0f2fe; color: #0369a1; }
.badge-prod { background: #fef3c7; color: #92400e; }
.badge-health { background: #dcfce7; color: #166534; }
.badge-carton { background: #ede9fe; color: #5b21b6; }

.org-title {
  margin: 0;
  font-size: 16px;
  font-weight: 800;
  color: #0f172a;
  line-height: 1.45;
}
.org-subtitle-inline {
  display: block;
  font-size: 12.5px;
  color: #64748b;
  margin-top: 4px;
}
.org-body {
  padding: 18px 20px 20px;
  display: flex;
  flex-direction: column;
  flex: 1;
}
.org-address-row {
  display: flex;
  align-items: flex-start;
  gap: 8px;
  margin-bottom: 16px;
  background: #f8fafc;
  padding: 10px 12px;
  border-radius: 10px;
  border: 1px solid #f1f5f9;
}
.org-address-row .icon {
  font-size: 16px;
  flex-shrink: 0;
  margin-top: 2px;
}
.address-text {
  margin: 0;
  font-size: 12.5px;
  line-height: 1.6;
  color: #334155;
  flex: 1;
}
.copy-btn {
  background: #ffffff;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  color: #64748b;
  cursor: pointer;
  padding: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
  flex-shrink: 0;
}
.copy-btn:hover {
  background: var(--primary);
  color: #ffffff;
  border-color: var(--primary);
}

.org-phones-section {
  margin-bottom: 12px;
}
.phones-title {
  display: block;
  font-size: 12px;
  font-weight: 700;
  color: #475569;
  margin-bottom: 8px;
}
.phones-buttons-row {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}
.call-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 7px 12px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 800;
  text-decoration: none;
  transition: all 0.2s ease;
  flex: 1 1 calc(50% - 6px);
  justify-content: center;
  direction: ltr;
}
.call-btn.primary {
  background: #eff6ff;
  color: #1d4ed8;
  border: 1px solid #bfdbfe;
}
.call-btn.primary:hover {
  background: #1d4ed8;
  color: #ffffff;
  border-color: #1d4ed8;
}
.call-btn.outline {
  background: #f8fafc;
  color: #475569;
  border: 1px solid #e2e8f0;
}
.call-btn.outline:hover {
  background: #e2e8f0;
  color: #0f172a;
}

.org-notice-box {
  display: flex;
  align-items: flex-start;
  gap: 8px;
  background: #fffbeb;
  border: 1px solid #fef3c7;
  padding: 8px 12px;
  border-radius: 8px;
  font-size: 11.5px;
  color: #92400e;
  line-height: 1.5;
  margin-bottom: 10px;
}
.org-notice-box p { margin: 0; }

.submit-org-cta {
  background: #ffffff;
  border: 1.5px dashed #cbd5e1;
  border-radius: 16px;
  padding: 24px 28px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
  flex-wrap: wrap;
}
.cta-text strong {
  display: block;
  font-size: 15px;
  color: #0f172a;
  margin-bottom: 4px;
}
.cta-text span {
  font-size: 13px;
  color: #64748b;
}
.cta-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: var(--primary);
  color: #ffffff;
  padding: 10px 20px;
  border-radius: 10px;
  font-weight: 700;
  font-size: 13.5px;
  text-decoration: none;
  white-space: nowrap;
}

.copy-toast {
  position: fixed;
  bottom: 24px;
  left: 50%;
  transform: translateX(-50%) translateY(100px);
  background: #0f172a;
  color: #ffffff;
  padding: 10px 22px;
  border-radius: 30px;
  font-size: 13px;
  font-weight: 700;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  opacity: 0;
  pointer-events: none;
  transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
  z-index: 9999;
}
.copy-toast.show {
  transform: translateX(-50%) translateY(0);
  opacity: 1;
}

@media (max-width: 640px) {
  .page-hero-card {
    padding: 28px 18px 24px;
    border-radius: 16px;
  }
  .page-hero-title {
    font-size: 21px;
  }
  .page-hero-desc {
    font-size: 13px;
  }
  .org-cards-grid {
    grid-template-columns: 1fr;
  }
  .submit-org-cta {
    flex-direction: column;
    text-align: center;
    padding: 20px;
  }
  .cta-btn {
    width: 100%;
    justify-content: center;
  }
}
</style>

<script>
function filterPhoneCards() {
  const query = document.getElementById('phoneSearchInput').value.trim().toLowerCase();
  const cards = document.querySelectorAll('.org-card');
  cards.forEach(card => {
    const text = card.getAttribute('data-search').toLowerCase();
    if (!query || text.includes(query)) {
      card.style.display = 'flex';
    } else {
      card.style.display = 'none';
    }
  });
}

function copyAddress(btn, text) {
  navigator.clipboard.writeText(text).then(() => {
    const toast = document.getElementById('copyToast');
    toast.classList.add('show');
    setTimeout(() => {
      toast.classList.remove('show');
    }, 2500);
  });
}
</script>
@endsection
