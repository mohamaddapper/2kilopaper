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

<!-- بخش جستجو و معرفی سایت -->
<section class="hero"><div class="container">
  <h1>دایرکتوری کاغذ، چاپ و بسته‌بندی ایران</h1>
  <p>شرکت، تأمین‌کننده و نیروی متخصص را پیدا کنید</p>
  <form class="search" action="{{ route('directory') }}">
    <input name="q" placeholder="نام شرکت یا محصول...">
    <button class="btn" style="background:#fff;color:var(--primary)">جستجو</button>
  </form>
</div></section>
@endsection

@section('content')
<h2>دسته‌بندی‌ها</h2>
<div class="grid">
@foreach($categories as $c)
  <a class="card" href="{{ route('directory', ['cat' => $c->id]) }}" style="color:inherit"><h3>{{ $c->name }}</h3></a>
@endforeach
</div>
@if($featured->count())
<h2 style="margin-top:32px">شرکت‌های ویژه</h2>
<div class="grid">@foreach($featured as $company)@include('partials.card')@endforeach</div>
@endif

<style>
/* استایل‌های اسلایدر تمام‌عرض ۵۰۰ پیکسلی با اثر ورق‌خور و نمایش بنرهای قبل و بعد (Center Mode Peek) */
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
  direction: ltr; /* جهت محاسبه ریاضی دقیق و مستقل از مرورگر */
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
.home-slider-prev {
  right: 20px;
}
.home-slider-next {
  left: 20px;
}
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

@media (max-width: 900px) {
  .home-banner-inner {
    padding: 32px 36px;
  }
  .home-banner-title {
    font-size: 24px;
  }
  .home-banner-desc {
    font-size: 14px;
  }
  .home-banner-graphic {
    font-size: 85px;
    margin-right: 20px;
  }
}

@media (max-width: 680px) {
  .home-slider-wrapper,
  .home-slider-track,
  .home-slider-item,
  .home-banner-card {
    height: 440px;
  }
  .home-banner-inner {
    padding: 24px 20px;
  }
  .home-banner-badge {
    font-size: 11.5px;
    padding: 5px 12px;
    margin-bottom: 14px;
  }
  .home-banner-title {
    font-size: 20px;
    margin-bottom: 12px;
    line-height: 1.4;
  }
  .home-banner-desc {
    font-size: 13px;
    margin-bottom: 20px;
    line-height: 1.6;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  .home-banner-btn {
    font-size: 13.5px;
    padding: 10px 20px;
  }
  .home-banner-graphic {
    display: none;
  }
  .home-slider-arrow {
    width: 36px;
    height: 36px;
  }
  .home-slider-prev { right: 8px; }
  .home-slider-next { left: 8px; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const wrapper = document.getElementById('homeSliderWrapper');
  const track = document.getElementById('homeSliderTrack');
  const items = document.querySelectorAll('.home-slider-item');
  const dots = document.querySelectorAll('.home-slider-dot');
  const totalRealSlides = 5;
  
  if (!wrapper || !track || items.length === 0) return;

  let currentTrackIndex = 1; // شروع از اسلاید واقعی ۱ (ایندکس ۱ در ترک)
  let isAnimating = false;
  let autoPlayTimer = null;

  function getSlideDimensions() {
    const wrapperW = wrapper.offsetWidth;
    const isMobile = window.innerWidth <= 768;
    const isSmallMobile = window.innerWidth <= 480;

    // بنر وسط حدود ۷۰٪ صفحه را پر می‌کند و ۱۵٪ از چپ و ۱۵٪ از راست به بنرهای قبلی و بعدی اختصاص می‌یابد
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

    // تعیین ایندکس اسلاید واقعی برای دات‌ها (۰ تا ۴)
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
    // چرخش پیوسته و بدون پرش (Infinite Loop)
    if (currentTrackIndex === 0) {
      currentTrackIndex = totalRealSlides; // پرش به اسلاید ۵ بدون انیمیشن
      updatePosition(false);
    } else if (currentTrackIndex === totalRealSlides + 1) {
      currentTrackIndex = 1; // پرش به اسلاید ۱ بدون انیمیشن
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

  // کلیک روی بنرهای کناری به صورت مستقیم آن بنر را به وسط می‌آورد
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

  // توقف پخش خودکار هنگام هاور ماوس روی اسلایدر
  wrapper.addEventListener('mouseenter', stopAutoPlay);
  wrapper.addEventListener('mouseleave', startAutoPlay);

  // سوایپ لمسی برای گوشی‌ها و تبلت‌ها
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

  // رسپانسیو بودن هنگام تغییر اندازه پنجره
  let resizeTimer;
  window.addEventListener('resize', function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function () {
      applyDimensions();
      updatePosition(false);
    }, 100);
  });

  // اجرای اولیه
  applyDimensions();
  updatePosition(false);
  startAutoPlay();
});
</script>
@endsection
