@extends('layouts.app', [
  'title' => 'سیاست‌نامه حریم خصوصی',
  'description' => 'اصول و تعهدات ۲ کیلو کاغذ در حفظ، نگهداری و صیانت از اطلاعات شخصی و هویتی کاربران'
])

@section('content')
<div class="privacy-page-container">

  <!-- هدر سیاست‌نامه -->
  <div class="privacy-hero">
    <div class="privacy-badge">
      <span>🔒</span>
      <span>امنیت و صیانت از داده‌ها</span>
    </div>
    <h1 class="privacy-title">سیاست‌نامه حریم خصوصی ۲ کیلو کاغذ</h1>
    <p class="privacy-desc">
      «۲ کیلو کاغذ» به حریم خصوصی کاربران خود عمیقاً احترام می‌گذارد و متعهد به حفاظت از اطلاعات هویتی و تجاری است که شما در اختیار سامانه قرار می‌دهید.
    </p>
    <div class="privacy-date">
      <span>🗓️</span>
      <span>آخرین ویرایش و به‌روزرسانی: ۳ خرداد ۱۴۰۲</span>
    </div>
  </div>

  <div class="privacy-card">

    <div class="privacy-intro-box">
      <p>
        این سند مطابق با آخرین تغییرات قوانین موضوعه کشور از جمله <strong>قانون تجارت الکترونیکی مصوب ۱۳۸۲</strong> و <strong>قانون انتشار و دسترسی آزاد به اطلاعات مصوب ۱۳۸۷</strong> تنظیم شده است. هدف از تدوین این سند، شفاف‌سازی کامل فرآیند جمع‌آوری، پردازش و حفاظت از داده‌ها در ۲ کیلو کاغذ می‌باشد.
      </p>
    </div>

    <!-- بخش ۱: اطلاعاتی که جمع‌آوری می‌شوند -->
    <div class="privacy-article">
      <div class="art-header">
        <span class="art-num">۱</span>
        <h2>کدام اطلاعات توسط ۲ کیلو کاغذ جمع‌آوری می‌شوند؟</h2>
      </div>
      <div class="art-content">
        <p>مطابق ماده قانونی، منظور از داده‌های شخصی اطلاعاتی است که مستقیماً یا به صورت معین مربوط به یک شخص حقیقی یا حقوقی باشد:</p>
        <ul>
          <li><strong>اطلاعات حساب کاربری:</strong> شماره تلفن همراه فعال جهت ورود و احراز هویت پیامکی، نام و نام خانوادگی، آدرس ایمیل.</li>
          <li><strong>اطلاعات واحد تولیدی و شرکتی:</strong> نام شرکت یا برند تجاری، زمینه فعالیت و رسته تولیدی، موقعیت مکانی (استان و شهر)، آدرس دفتر و کارخانه، شماره‌های تماس مستقیم، فکس، لوگو و تصاویر محصولات.</li>
          <li><strong>اطلاعات فنی و دستگاه:</strong> مدل دستگاه، نوع سیستم‌عامل، نگارش مرورگر و آدرس IP صرفاً به منظور جلوگیری از تقلب، حملات DDoS و تامین امنیت سرورها.</li>
        </ul>
      </div>
    </div>

    <hr class="privacy-divider">

    <!-- بخش ۲: اهداف استفاده از اطلاعات -->
    <div class="privacy-article">
      <div class="art-header">
        <span class="art-num">۲</span>
        <h2>از اطلاعات شخصی چه استفاده‌ای می‌کنیم؟</h2>
      </div>
      <div class="art-content">
        <p>اطلاعات ثبت‌شده توسط کاربران صرفاً در جهت تحقق اهداف زیر مورد استفاده قرار می‌گیرد:</p>
        <ul>
          <li>نمایش عمومی مشخصات شرکت در دایرکتوری تخصصی جهت جذب مشتریان و خریداران بالقوه</li>
          <li>امکان برقراری تماس بی‌واسطه میان خریداران و مدیران فروش مجموعه‌ها</li>
          <li>ارسال پیامک‌های ضروری حاوی کدهای ورود یک‌بارمصرف و وضعیت تایید شرکت</li>
          <li>پیشگیری از فعالیت حساب‌های جعلی، اسپم و ارتقای امنیت فضای تبادل تجاری</li>
          <li>بهبود مستمر تجربه کاربری و سرعت خدمات مبتنی بر علایق صنفی مخاطبان</li>
        </ul>
      </div>
    </div>

    <hr class="privacy-divider">

    <!-- بخش ۳: امنیت داده‌ها -->
    <div class="privacy-article">
      <div class="art-header">
        <span class="art-num">۳</span>
        <h2>امنیت اطلاعات و تدابیر حفاظتی</h2>
      </div>
      <div class="art-content">
        <p>
          ۲ کیلو کاغذ کلیه اطلاعات حساس کاربران را بر روی سرورهای امن در داخل کشور و با استفاده از پروتکل‌های رمزنگاری معتبر (SSL/TLS) نگهداری می‌نماید. ما متعهدیم که اطلاعات تماس محرمانه و کدهای هویتی کاربران را تحت هیچ شرایطی در اختیار اشخاص ثالث یا شرکت‌های تبلیغاتی متفرقه قرار ندهیم، مگر در مواردی که به حکم مراجع صالح قضایی الزام قانونی وجود داشته باشد.
        </p>
      </div>
    </div>

    <hr class="privacy-divider">

    <!-- بخش ۴: لینک‌ها و خدمات اشخاص ثالث -->
    <div class="privacy-article">
      <div class="art-header">
        <span class="art-num">۴</span>
        <h2>سایت‌ها و خدمات اشخاص ثالث</h2>
      </div>
      <div class="art-content">
        <p>
          صفحات اختصاصی شرکت‌ها ممکن است حاوی آدرس وب‌سایت یا لینک‌های شبکه‌های اجتماعی اختصاصی آنان باشد. از آنجا که این سایت‌ها خارج از کنترل سامانه ۲ کیلو کاغذ مدیریت می‌شوند، توصیه می‌گردد پیش از ارائه اطلاعات در آن وب‌سایت‌ها، سیاست‌نامه حریم خصوصی اختصاصی آن‌ها را مطالعه فرمایید.
        </p>
      </div>
    </div>

    <hr class="privacy-divider">

    <!-- بخش ۵: حقوق کاربران در ویرایش و حذف -->
    <div class="privacy-article">
      <div class="art-header">
        <span class="art-num">۵</span>
        <h2>حقوق شما در ویرایش و به‌روزرسانی اطلاعات</h2>
      </div>
      <div class="art-content">
        <p>
          کاربران همواره از طریق بخش «میز کار» دسترسی کامل به ویرایش تلفن‌ها، آدرس‌ها، توضیحات و تصاویر شرکت خود را دارند. چنانچه در خصوص شیوه نگهداری داده‌ها پرسشی دارید یا مایل به گزارش هرگونه مغایرت هستید، می‌توانید با آدرس ایمیل <a href="mailto:support@2kilopaper.ir">support@2kilopaper.ir</a> یا شماره پشتیبانی در تماس باشید.
        </p>
      </div>
    </div>

  </div>

</div>

<style>
.privacy-page-container {
  width: 100%;
  margin: 24px 0 60px;
  padding: 0;
}
.privacy-hero {
  background: linear-gradient(135deg, #064e3b 0%, #047857 60%, #059669 100%);
  color: #ffffff;
  border-radius: 20px;
  padding: 40px 32px 34px;
  text-align: center;
  margin-bottom: 28px;
  box-shadow: 0 10px 30px rgba(6, 78, 59, 0.15);
}
.privacy-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 5px 14px;
  background: rgba(255, 255, 255, 0.16);
  backdrop-filter: blur(8px);
  border-radius: 30px;
  font-size: 13px;
  font-weight: 700;
  margin-bottom: 14px;
  border: 1px solid rgba(255, 255, 255, 0.22);
}
.privacy-title {
  margin: 0 0 12px;
  font-size: 26px;
  font-weight: 900;
  letter-spacing: -0.4px;
}
.privacy-desc {
  max-width: 720px;
  margin: 0 auto 16px;
  font-size: 14px;
  line-height: 1.8;
  color: #d1fae5;
}
.privacy-date {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 12.5px;
  color: #a7f3d0;
  background: rgba(0, 0, 0, 0.22);
  padding: 4px 14px;
  border-radius: 20px;
}

.privacy-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 20px;
  padding: 36px 36px 40px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
}
.privacy-intro-box {
  background: #f0fdf4;
  border: 1.5px solid #bbf7d0;
  border-radius: 14px;
  padding: 16px 20px;
  margin-bottom: 28px;
}
.privacy-intro-box p {
  margin: 0;
  font-size: 14px;
  line-height: 1.85;
  color: #166534;
}

.privacy-article {
  padding: 8px 0;
}
.art-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 14px;
}
.art-num {
  width: 30px;
  height: 30px;
  border-radius: 8px;
  background: #ecfdf5;
  color: #047857;
  border: 1px solid #a7f3d0;
  font-size: 14px;
  font-weight: 900;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.art-header h2 {
  margin: 0;
  font-size: 18.5px;
  font-weight: 900;
  color: #0f172a;
}
.art-content p {
  color: #475569;
  font-size: 14px;
  line-height: 1.85;
  margin: 0 0 14px;
}
.art-content ul {
  margin: 0 0 14px 20px;
  padding: 0;
  color: #334155;
  font-size: 14px;
  line-height: 1.85;
}
.art-content li {
  margin-bottom: 8px;
}
.art-content strong {
  color: #0f172a;
}
.art-content a {
  color: var(--primary);
  font-weight: 700;
}

.privacy-divider {
  border: none;
  height: 1px;
  background: #f1f5f9;
  margin: 26px 0;
}

@media (max-width: 640px) {
  .privacy-hero {
    padding: 28px 18px 24px;
  }
  .privacy-title {
    font-size: 21px;
  }
  .privacy-card {
    padding: 24px 18px;
  }
  .art-header h2 {
    font-size: 16px;
  }
}
</style>
@endsection
