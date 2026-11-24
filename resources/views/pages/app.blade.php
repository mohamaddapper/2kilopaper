@extends('layouts.app', [
  'title' => 'دانلود اپلیکیشن ۲ کیلو کاغذ',
  'description' => 'دانلود مستقیم اپلیکیشن اندروید و راهنمای نصب نسخه وب‌اپلیکیشن آیفون (iOS) ۲ کیلو کاغذ'
])

@section('content')
<div class="app-page-container">

  <!-- بنر هدر معرفی اپلیکیشن -->
  <div class="app-hero">
    <div class="app-hero-badge">
      <span>📱</span>
      <span>دسترسی سریع و همیشه همراه</span>
    </div>
    <h1 class="app-hero-title">اپلیکیشن ۲ کیلو کاغذ</h1>
    <p class="app-hero-desc">
      با نصب اپلیکیشن ۲ کیلو کاغذ روی تلفن همراه خود، در هر زمان و مکان به جامع‌ترین بانک اطلاعاتی شرکت‌ها، بارهای آخال و آگهی‌های استخدام صنعت کاغذ و بسته‌بندی کشور دسترسی داشته باشید.
    </p>
  </div>

  <!-- دو ستون دانلود: اندروید و iOS -->
  <div class="platforms-grid">

    <!-- کارت اندروید -->
    <div class="platform-card card-android">
      <div class="platform-badge">
        <span>🤖</span>
        <span>سیستم‌عامل اندروید</span>
      </div>
      <h2 class="platform-title">نسخه اندروید (Android)</h2>
      <p class="platform-version">نسخه رسمی ۴.۴ (پایدار و سبک)</p>
      
      <div class="platform-features">
        <div class="feat-item">
          <span class="feat-icon">⚡</span>
          <span>سرعت بالا در جستجوی شرکت‌ها و تامین‌کنندگان</span>
        </div>
        <div class="feat-item">
          <span class="feat-icon">🔔</span>
          <span>دریافت فوری پیام‌ها، پاسخ استعلام‌ها و آگهی‌ها</span>
        </div>
        <div class="feat-item">
          <span class="feat-icon">📦</span>
          <span>امکان ثبت سریع بار آخال و فرصت‌های شغلی</span>
        </div>
        <div class="feat-item">
          <span class="feat-icon">📶</span>
          <span>مصرف اینترنت نیم‌بها و کارکرد روان در تمام نسخه‌ها</span>
        </div>
      </div>

      <div class="download-action-wrap">
        <a href="https://2kilopaper.ir/dl-fls/app/2kilopaper-v4.4.apk" class="dl-btn android-btn" download>
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
          <div class="dl-btn-text">
            <strong>دانلود مستقیم نسخه اندروید</strong>
            <span>فایل نصبی APK · حجم کم · نسخه ۴.۴</span>
          </div>
        </a>
      </div>
    </div>

    <!-- کارت آیفون iOS -->
    <div class="platform-card card-ios">
      <div class="platform-badge badge-ios">
        <span>🍎</span>
        <span>گوشی‌های آیفون (iOS)</span>
      </div>
      <h2 class="platform-title">وب‌اپلیکیشن آیفون (iOS PWA)</h2>
      <p class="platform-version">بدون نیاز به اپ‌استور و تحریم، همیشه به‌روز</p>

      <p class="ios-intro">
        اگر از گوشی آیفون استفاده می‌کنید، نیازی به نصب فایل جداگانه نیست. با چند ثانیه انجام مراحل زیر، آیکون ۲ کیلو کاغذ همانند سایر اپلیکیشن‌ها به صفحه گوشی شما اضافه می‌شود:
      </p>

      <!-- مراحل نصب در iOS -->
      <div class="ios-steps-list">
        <div class="ios-step">
          <div class="step-num">۱</div>
          <div class="step-desc">
            سایت ۲ کیلو کاغذ را در مرورگر <strong>Safari</strong> باز کنید و در نوار پایین صفحه دکمه <strong>Share (اشتراک‌گذاری)</strong> را لمس کنید.
          </div>
        </div>

        <div class="ios-step">
          <div class="step-num">۲</div>
          <div class="step-desc">
            در منوی بازشده به سمت پایین اسکرول کرده و گزینه <strong>Add to Home Screen (افزودن به صفحه اصلی)</strong> را انتخاب کنید.
          </div>
        </div>

        <div class="ios-step">
          <div class="step-num">۳</div>
          <div class="step-desc">
            در گوشه بالا سمت راست دکمه <strong>Add (افزودن)</strong> را بزنید. اپلیکیشن با دسترسی تمام‌صفحه به گوشی شما افزوده شد!
          </div>
        </div>
      </div>

      <div class="ios-status-badge">
        <span>✓</span>
        <span>سازگار با تمامی مدل‌های آیفون و آیپد با سیستم‌عامل iOS 11 به بالا</span>
      </div>
    </div>

  </div>

  <!-- کارت ویژگی‌های اختصاصی اپلیکیشن -->
  <div class="app-benefits-section">
    <h3 class="benefits-title">چرا نصب اپلیکیشن ۲ کیلو کاغذ؟</h3>
    <div class="benefits-grid">
      <div class="benefit-item">
        <div class="b-icon">🔍</div>
        <h4>جستجوی هوشمند در جیب شما</h4>
        <p>دسترسی به هزاران چاپخانه، کارتن‌سازی و واردکننده بر اساس شهر و تخصص بدون نیاز به جستجو در وب.</p>
      </div>
      <div class="benefit-item">
        <div class="b-icon">📞</div>
        <h4>تماس یک‌کلیکه با مدیران</h4>
        <p>با یک لمس، با شماره‌های مستقیم و موبایل مدیران و مسئولان فروش شرکت‌ها ارتباط تلفنی برقرار کنید.</p>
      </div>
      <div class="benefit-item">
        <div class="b-icon">💼</div>
        <h4>کاریابی سریع صنعت</h4>
        <p>آگهی‌های استخدامی جدید در صنایع چاپ، بسته‌بندی و کاغذ را سریع‌تر از سایرین مشاهده کرده و رزومه بفرستید.</p>
      </div>
    </div>
  </div>

</div>

<style>
.app-page-container {
  max-width: 1040px;
  margin: 32px auto 60px;
  padding: 0 16px;
}
.app-hero {
  background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 60%, #0369a1 100%);
  color: #ffffff;
  border-radius: 20px;
  padding: 42px 36px 36px;
  text-align: center;
  margin-bottom: 32px;
  box-shadow: 0 10px 30px rgba(15, 23, 42, 0.15);
}
.app-hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 5px 14px;
  background: rgba(255, 255, 255, 0.16);
  backdrop-filter: blur(8px);
  border-radius: 30px;
  font-size: 13px;
  font-weight: 700;
  margin-bottom: 16px;
  border: 1px solid rgba(255, 255, 255, 0.22);
}
.app-hero-title {
  margin: 0 0 12px;
  font-size: 28px;
  font-weight: 900;
  letter-spacing: -0.4px;
}
.app-hero-desc {
  max-width: 720px;
  margin: 0 auto;
  font-size: 14.5px;
  line-height: 1.8;
  color: #e2e8f0;
}

.platforms-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 24px;
  margin-bottom: 40px;
}
.platform-card {
  background: #ffffff;
  border: 1.5px solid #e2e8f0;
  border-radius: 20px;
  padding: 32px 28px;
  display: flex;
  flex-direction: column;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
  transition: all 0.25s ease;
}
.platform-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 28px rgba(0, 0, 0, 0.08);
}
.card-android {
  border-top: 5px solid #10b981;
}
.card-ios {
  border-top: 5px solid #0284c7;
}

.platform-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  align-self: flex-start;
  font-size: 12px;
  font-weight: 800;
  padding: 4px 12px;
  border-radius: 20px;
  background: #ecfdf5;
  color: #047857;
  border: 1px solid #a7f3d0;
  margin-bottom: 14px;
}
.badge-ios {
  background: #f0f9ff;
  color: #0369a1;
  border-color: #bae6fd;
}
.platform-title {
  margin: 0 0 6px;
  font-size: 20px;
  font-weight: 900;
  color: #0f172a;
}
.platform-version {
  margin: 0 0 20px;
  font-size: 13px;
  font-weight: 600;
  color: #64748b;
}

.platform-features {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-bottom: 26px;
  flex: 1;
}
.feat-item {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  font-size: 13.5px;
  color: #334155;
  line-height: 1.5;
}
.feat-icon {
  font-size: 16px;
  flex-shrink: 0;
}

.download-action-wrap {
  margin-top: auto;
}
.dl-btn {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 14px 20px;
  border-radius: 12px;
  text-decoration: none;
  transition: all 0.2s ease;
}
.android-btn {
  background: linear-gradient(135deg, #059669 0%, #10b981 100%);
  color: #ffffff;
  box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);
}
.android-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4);
}
.dl-btn-text strong {
  display: block;
  font-size: 15px;
  font-weight: 800;
  margin-bottom: 2px;
}
.dl-btn-text span {
  display: block;
  font-size: 12px;
  opacity: 0.9;
}

.ios-intro {
  font-size: 13.5px;
  line-height: 1.7;
  color: #475569;
  margin: 0 0 18px;
}
.ios-steps-list {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-bottom: 22px;
  flex: 1;
}
.ios-step {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  background: #f8fafc;
  padding: 10px 14px;
  border-radius: 10px;
  border: 1px solid #f1f5f9;
}
.step-num {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background: #0284c7;
  color: #ffffff;
  font-size: 12px;
  font-weight: 900;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  margin-top: 2px;
}
.step-desc {
  font-size: 12.5px;
  line-height: 1.6;
  color: #334155;
}
.ios-status-badge {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 12px;
  color: #0369a1;
  background: #f0f9ff;
  border: 1px solid #bae6fd;
  padding: 8px 12px;
  border-radius: 10px;
  font-weight: 600;
  margin-top: auto;
}

.app-benefits-section {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 20px;
  padding: 34px 32px 30px;
  box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
}
.benefits-title {
  text-align: center;
  font-size: 21px;
  font-weight: 900;
  color: #0f172a;
  margin: 0 0 24px;
}
.benefits-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
}
.benefit-item {
  text-align: center;
  padding: 12px;
}
.b-icon {
  font-size: 34px;
  margin-bottom: 12px;
}
.benefit-item h4 {
  margin: 0 0 8px;
  font-size: 15.5px;
  font-weight: 800;
  color: #0f172a;
}
.benefit-item p {
  margin: 0;
  font-size: 13px;
  color: #64748b;
  line-height: 1.7;
}

@media (max-width: 860px) {
  .platforms-grid {
    grid-template-columns: 1fr;
  }
  .benefits-grid {
    grid-template-columns: 1fr;
    gap: 16px;
  }
}
@media (max-width: 640px) {
  .app-hero {
    padding: 30px 18px 24px;
  }
  .app-hero-title {
    font-size: 22px;
  }
  .platform-card {
    padding: 24px 18px;
  }
}
</style>
@endsection
