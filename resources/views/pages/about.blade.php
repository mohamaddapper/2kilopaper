@extends('layouts.app', [
  'title' => 'درباره ما',
  'description' => 'درباره ۲ کیلو کاغذ؛ مرکز و مرجع تخصصی صنعت کاغذ، مقوا، کارتن، چاپ و بسته‌بندی ایران'
])

@section('content')
<div class="about-container">

  <!-- بنر معرفی بالای صفحه -->
  <div class="about-hero">
    <div class="about-hero-badge">
      <span>🏢</span>
      <span>شناسنامه و رسالت مجموعه</span>
    </div>
    <h1 class="about-hero-title">مرکز صنعت کاغذ، چاپ و بسته‌بندی ایران</h1>
    <p class="about-hero-subtitle">
      ۲ کیلو کاغذ؛ پلتفرمی هوشمند برای پیوند مستقیم میان تولیدکنندگان، چاپخانه‌داران، بازرگانان و خریداران صنایع سلولزی کشور
    </p>
  </div>

  <!-- داستان ما -->
  <div class="about-story-card">
    <div class="story-header">
      <span class="story-tag">داستان شکل‌گیری</span>
      <h2>از یک هدف ساده و شفاف تا بزرگ‌ترین شبکه صنعتی</h2>
    </div>
    <p class="story-lead">
      «۲ کیلو کاغذ» از همان ابتدا با یک هدف ساده، شفاف و بنیادین راه‌اندازی شد: <strong>گردآوری و دسته‌بندی دقیق تمام شرکت‌ها، کارخانجات و کارگاه‌های فعال در حوزه کاغذ، مقوا، کارتن، چاپ و بسته‌بندی در سطح ایران</strong>؛ تا فعالان اقتصادی بتوانند به آسانی تامین‌کننده، چاپخانه یا خدمات‌دهنده تخصصی مورد نظر خود را متناسب با نیاز فنی و موقعیت جغرافیایی پیدا کنند.
    </p>
    <p class="story-text">
      ما در ۲ کیلو کاغذ عمیقاً باور داریم که <strong>شفافیت، دسته‌بندی مویرگی و دسترسی سریع به اطلاعات موثق</strong>، مهم‌ترین پیش‌نیاز رشد بازار و رونق تولید ملی است. به همین دلیل، تک‌تک کسب‌وکارهایی که در سایت ثبت می‌شوند، با نظارت کارشناسان بررسی شده و بر اساس زمینه فعالیت، نوع ماشین‌آلات و موقعیت مکانی ایندکس می‌گردند.
    </p>
  </div>

  <!-- جامعه مخاطبان: مرجع چه بخش‌هایی هستیم؟ -->
  <div class="audiences-section">
    <h3 class="section-heading">امروز، ۲ کیلو کاغذ مرجعی معتبر است برای:</h3>
    
    <div class="audience-grid">
      <div class="audience-card">
        <div class="aud-icon">🏭</div>
        <h4>تولید و واردات مواد اولیه</h4>
        <p>تولیدکنندگان و بازرگانان انواع خمیر کاغذ، کاغذ تست‌لاینر، فلوتینگ، وایت‌تاپ، مقوای ایندربرد، فیلم، فویل و مرکب چاپ.</p>
      </div>

      <div class="audience-card">
        <div class="aud-icon">🖨️</div>
        <h4>چاپخانه‌های تخصصی</h4>
        <p>مراکز چاپ در شاخه‌های چاپ افست، فلکسوگرافی، روتوگراور، چاپ دیجیتال، لیبل، جعبه‌های دارویی، آرایشی و غذایی.</p>
      </div>

      <div class="audience-card">
        <div class="aud-icon">📦</div>
        <h4>کارتن‌سازی و جعبه‌سازی</h4>
        <p>واحدهای تولید ورق کارتن سه لا و پنج لا، کارتن لمینتی، دایکاتی، کارتن‌های پستی و جعبه‌های هاردباکس لوکس.</p>
      </div>

      <div class="audience-card">
        <div class="aud-icon">✂️</div>
        <h4>خدمات پس از چاپ</h4>
        <p>کارگاه‌های تخصصی دایکات، لترپرس، سلفون‌کشی مات و براق، طلاکوب، برجسته‌کاری، لمینت گرم و سرد و یووی موضعی.</p>
      </div>

      <div class="audience-card">
        <div class="aud-icon">⚙️</div>
        <h4>ماشین‌آلات و قطعات</h4>
        <p>سازندگان، واردکنندگان و ارائه‌دهندگان ماشین‌آلات خطوط تولید کاغذ، دستگاه‌های چاپ، تیغه‌ها و خدمات فنی مهندسی.</p>
      </div>

      <div class="audience-card">
        <div class="aud-icon">♻️</div>
        <h4>بار آخال و ضایعات بازیافتی</h4>
        <p>خریداران و فروشندگان بارهای ضایعات کاغذ و کارتن (آخال) جهت تامین خوراک پایدار کارخانجات بازیافت کشور.</p>
      </div>
    </div>
  </div>

  <!-- چرا ۲ کیلو کاغذ؟ -->
  <div class="why-us-card">
    <h3 class="why-title">چرا فعالان صنعت ۲ کیلو کاغذ را انتخاب می‌کنند؟</h3>
    
    <div class="features-list">
      <div class="feat-row">
        <div class="f-num">★</div>
        <div class="f-text">
          <strong>پوشش ۳۶۰ درجه صنعت:</strong>
          <span>از تامین الیاف و مواد خام تا طراحی، چاپ، بسته‌بندی، انبارش و بازیافت در یک زنجیره یکپارچه.</span>
        </div>
      </div>

      <div class="feat-row">
        <div class="f-num">★</div>
        <div class="f-text">
          <strong>دسته‌بندی هوشمند و تخصصی:</strong>
          <span>امکان تفکیک بر اساس نوع محصول، شیوه چاپ، استان و شهر جهت جلوگیری از سردرگمی مشتریان.</span>
        </div>
      </div>

      <div class="feat-row">
        <div class="f-num">★</div>
        <div class="f-text">
          <strong>ارتباط مستقیم و حذف واسطه‌ها:</strong>
          <span>دسترسی مستقیم خریداران به تلفن‌ها، آدرس کارخانه و شبکه‌های ارتباطی مدیران بدون پرداخت کارمزد.</span>
        </div>
      </div>

      <div class="feat-row">
        <div class="f-num">★</div>
        <div class="f-text">
          <strong>کاریابی تخصصی درون‌صنعتی:</strong>
          <span>پیوند میان کارجویان ماهر (اپراتور چاپ، قالب‌ساز، طراح کارتن) و کارفرمایان تولیدی.</span>
        </div>
      </div>
    </div>
  </div>

  <!-- کادر ترغیب به ثبت شرکت -->
  <div class="about-cta-bar">
    <div class="cta-inner">
      <div class="cta-msg">
        <strong>شما هم صاحب کسب‌وکار یا کارگاه در این صنعت هستید؟</strong>
        <span>همین امروز با ثبت مشخصات مجموعه خود در ۲ کیلو کاغذ، در دید هزاران سفارش‌دهنده روزانه قرار بگیرید.</span>
      </div>
      <a href="{{ route('companies.create') }}" class="btn cta-action-btn">
        <span>ثبت شرکت در دایرکتوری</span>
        <span>←</span>
      </a>
    </div>
  </div>

</div>

<style>
.about-container {
  max-width: 1040px;
  margin: 32px auto 60px;
  padding: 0 16px;
}
.about-hero {
  background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #0284c7 100%);
  color: #ffffff;
  border-radius: 20px;
  padding: 44px 36px 38px;
  text-align: center;
  box-shadow: 0 10px 30px rgba(30, 58, 138, 0.16);
  margin-bottom: 32px;
}
.about-hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 5px 14px;
  background: rgba(255, 255, 255, 0.18);
  backdrop-filter: blur(8px);
  border-radius: 30px;
  font-size: 13px;
  font-weight: 700;
  margin-bottom: 16px;
  border: 1px solid rgba(255, 255, 255, 0.25);
}
.about-hero-title {
  margin: 0 0 14px;
  font-size: 28px;
  font-weight: 900;
  letter-spacing: -0.4px;
}
.about-hero-subtitle {
  max-width: 740px;
  margin: 0 auto;
  font-size: 15px;
  line-height: 1.8;
  color: #e0f2fe;
}

.about-story-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 20px;
  padding: 38px 36px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
  margin-bottom: 36px;
}
.story-header {
  margin-bottom: 18px;
}
.story-tag {
  display: inline-block;
  font-size: 12px;
  font-weight: 800;
  color: #2563eb;
  background: #eff6ff;
  border: 1px solid #dbeafe;
  padding: 3px 12px;
  border-radius: 20px;
  margin-bottom: 10px;
}
.story-header h2 {
  margin: 0;
  font-size: 22px;
  font-weight: 900;
  color: #0f172a;
  letter-spacing: -0.3px;
}
.story-lead {
  font-size: 15px;
  line-height: 1.9;
  color: #334155;
  margin: 0 0 16px;
}
.story-text {
  font-size: 14.5px;
  line-height: 1.9;
  color: #475569;
  margin: 0;
}

.audiences-section {
  margin-bottom: 40px;
}
.section-heading {
  font-size: 21px;
  font-weight: 900;
  color: #0f172a;
  text-align: center;
  margin: 0 0 24px;
}
.audience-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(310px, 1fr));
  gap: 20px;
}
.audience-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  padding: 26px 24px;
  box-shadow: 0 3px 14px rgba(0, 0, 0, 0.03);
  transition: all 0.25s ease;
}
.audience-card:hover {
  transform: translateY(-3px);
  border-color: #cbd5e1;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
}
.aud-icon {
  font-size: 32px;
  margin-bottom: 12px;
}
.audience-card h4 {
  margin: 0 0 8px;
  font-size: 16px;
  font-weight: 800;
  color: #0f172a;
}
.audience-card p {
  margin: 0;
  font-size: 13px;
  line-height: 1.75;
  color: #64748b;
}

.why-us-card {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 20px;
  padding: 34px 32px;
  margin-bottom: 36px;
}
.why-title {
  font-size: 19px;
  font-weight: 900;
  color: #0f172a;
  margin: 0 0 20px;
  text-align: center;
}
.features-list {
  display: flex;
  flex-direction: column;
  gap: 14px;
}
.feat-row {
  display: flex;
  align-items: flex-start;
  gap: 14px;
  background: #ffffff;
  border: 1px solid #eef2f6;
  padding: 14px 18px;
  border-radius: 12px;
}
.f-num {
  color: #d97706;
  font-size: 18px;
  flex-shrink: 0;
  margin-top: 1px;
}
.f-text {
  font-size: 13.5px;
  line-height: 1.7;
  color: #475569;
}
.f-text strong {
  color: #0f172a;
  margin-left: 4px;
}

.about-cta-bar {
  background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
  color: #ffffff;
  border-radius: 18px;
  padding: 26px 32px;
  box-shadow: 0 8px 25px rgba(15, 23, 42, 0.15);
}
.cta-inner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
  flex-wrap: wrap;
}
.cta-msg strong {
  display: block;
  font-size: 16px;
  font-weight: 800;
  margin-bottom: 4px;
}
.cta-msg span {
  font-size: 13px;
  color: #94a3b8;
}
.cta-action-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: var(--primary);
  color: #ffffff;
  padding: 12px 22px;
  border-radius: 10px;
  font-weight: 800;
  font-size: 14px;
  text-decoration: none;
  white-space: nowrap;
}

@media (max-width: 640px) {
  .about-hero {
    padding: 30px 18px 26px;
  }
  .about-hero-title {
    font-size: 22px;
  }
  .about-story-card {
    padding: 24px 18px;
  }
  .cta-inner {
    flex-direction: column;
    text-align: center;
  }
  .cta-action-btn {
    width: 100%;
    justify-content: center;
  }
}
</style>
@endsection
