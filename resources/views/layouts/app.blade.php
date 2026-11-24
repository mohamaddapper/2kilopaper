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
@stack('head')
</head>
<body>
<header class="site"><div class="container nav">
  <a class="logo" href="{{ route('home') }}">{{ config('app.name') }}</a>
  @if(!empty($dashboardNav))
    <div style="display:flex;align-items:center;gap:18px">
      @if(auth()->check() && auth()->user()->isAdmin())
        <a href="{{ route('admin.dashboard') }}" style="color:#d97706;font-size:14px;font-weight:700;text-decoration:none">★ پنل مدیریت</a>
      @endif
      <a href="{{ route('directory') }}" style="color:var(--muted);font-size:14px;text-decoration:none">دایرکتوری</a>
      <a href="{{ route('jobs.index') }}" style="color:var(--muted);font-size:14px;text-decoration:none">فرصت‌های شغلی</a>
      <form method="post" action="{{ route('logout') }}" style="display:inline;margin:0">
        @csrf
        <button type="submit" style="background:none;border:none;color:var(--text);font-size:14.5px;font-family:inherit;cursor:pointer;padding:0;font-weight:500">خروج</button>
      </form>
    </div>
  @else
    <ul>
      @if(auth()->check() && auth()->user()->isAdmin())
        <li><a href="{{ route('admin.dashboard') }}" style="color:#d97706;font-weight:700">★ مدیریت</a></li>
      @endif
      <li><a href="{{ route('directory') }}">دایرکتوری</a></li>
      <li><a href="{{ route('jobs.index') }}">فرصت‌های شغلی</a></li>
      <li><a href="{{ route('subscription.fee') }}">تعرفه‌ها</a></li>
      <li><a href="{{ route('advertising') }}">تبلیغات</a></li>
      <li><a href="{{ route('pages.app') }}">اپلیکیشن</a></li>
      <li><a href="{{ route('pages.contact') }}">تماس با ما</a></li>
      @auth
        <li><a href="{{ route('dashboard') }}">میز کار</a></li>
        <li><form method="post" action="{{ route('logout') }}">@csrf<button class="btn ghost small">خروج</button></form></li>
      @else
        <li><a href="{{ route('login') }}">ورود</a></li>
      @endauth
    </ul>
  @endif
</div></header>
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
            <span>💬 بله</span>
          </a>
          <a href="https://t.me/dokilopaper" target="_blank" rel="noopener" class="footer-social-btn" title="تلگرام">
            <span>✈️ تلگرام</span>
          </a>
          <a href="https://instagram.com/2kilopaper" target="_blank" rel="noopener" class="footer-social-btn" title="اینستاگرام">
            <span>📷 اینستاگرام</span>
          </a>
        </div>
      </div>

      <!-- ستون ۲: دسترسی سریع و خدمات -->
      <div class="footer-col">
        <h4 class="footer-col-title">دسترسی سریع</h4>
        <ul class="footer-links">
          <li><a href="{{ route('home') }}">صفحه اصلی</a></li>
          <li><a href="{{ route('directory') }}">دایرکتوری و جستجوی شرکت‌ها</a></li>
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
          <span class="f-icon">📞</span>
          <div class="f-info">
            <span class="f-lbl">پشتیبانی تلفنی:</span>
            <a href="tel:02128421075" class="f-val ltr-text">۰۲۱-۲۸۴۲۱۰۷۵</a>
          </div>
        </div>

        <div class="footer-contact-item">
          <span class="f-icon">📧</span>
          <div class="f-info">
            <span class="f-lbl">پست الکترونیکی:</span>
            <a href="mailto:support@2kilopaper.ir" class="f-val ltr-text">support@2kilopaper.ir</a>
          </div>
        </div>

        <div class="footer-contact-item">
          <span class="f-icon">📍</span>
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
</style>
</body>
</html>
