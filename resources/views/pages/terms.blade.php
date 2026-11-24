@extends('layouts.app', [
  'title' => 'شرایط و قوانین استفاده از خدمات',
  'description' => 'شرایط، مقررات و قوانین استفاده از خدمات، ثبت شرکت و فعالیت در دایرکتوری ۲ کیلو کاغذ'
])

@section('content')
<div class="legal-page-container">

  <!-- هدر صفحه قوانین -->
  <div class="legal-hero">
    <div class="legal-badge">
      <span>📜</span>
      <span>قوانین و ضوابط رسمی</span>
    </div>
    <h1 class="legal-title">شرایط و قوانین استفاده از سرویس‌ها و خدمات ۲ کیلو کاغذ</h1>
    <p class="legal-desc">
      به منظور حفظ سلامت تعاملات تجاری، شفافیت اطلاعات و ارتقای کیفیت زنجیره تامین صنعت کاغذ، مقوا، کارتن و چاپ کشور، مجموعه ضوابط زیر به عنوان توافق‌نامه الزام‌آور بین کاربران و پلتفرم ۲ کیلو کاغذ تدوین شده است.
    </p>
    <div class="legal-date">
      <span>🗓️</span>
      <span>آخرین به‌روزرسانی و بازنگری: ۳ خرداد ۱۴۰۲</span>
    </div>
  </div>

  <!-- دسترسی سریع به بخش‌های قوانین -->
  <div class="legal-nav-pills">
    <a href="#section-reg" class="pill-link">شرایط ثبت کسب‌وکار</a>
    <a href="#section-images" class="pill-link">شرایط انتخاب تصویر و لوگو</a>
    <a href="#section-view" class="pill-link">شرایط استفاده و مشاهده</a>
    <a href="#section-general" class="pill-link">قوانین و مقررات کلی پلتفرم</a>
  </div>

  <div class="legal-content-card">

    <!-- بخش ۱: شرایط ثبت کسب و کار -->
    <section id="section-reg" class="legal-section">
      <div class="section-title-wrap">
        <span class="sec-num">۱</span>
        <h2>شرایط ثبت کسب‌وکار در ۲ کیلو کاغذ چیست؟</h2>
      </div>
      <p>به منظور افزایش کیفیت اطلاعات و اعتبار شرکت‌های معرفی‌شده، رعایت موارد زیر جهت ثبت کسب‌وکار الزامی است:</p>
      <ul class="styled-list">
        <li><strong>ماندگاری اطلاعات:</strong> شرکت‌ها و کسب‌وکارهای منتشرشده به صورت نامحدود در دایرکتوری ۲ کیلو کاغذ باقی می‌مانند.</li>
        <li><strong>ثبت چندباره:</strong> هر کاربر می‌تواند با یک حساب کاربری واحد، چندین کسب‌وکار، شعبه یا واحد تولیدی مجزا را ثبت و مدیریت کند.</li>
        <li><strong>اطلاعات حقیقی و معتبر:</strong> کاربر موظف است صرفاً از اطلاعات واقعی، پروانه‌ها و شماره تماس‌های صحیح استفاده نماید. در صورت احراز هرگونه مغایرت یا ثبت صوری، کسب‌وکار حذف گردیده و دسترسی کاربر مسدود خواهد شد.</li>
        <li><strong>به‌روزرسانی دوره‌ای:</strong> کارفرما موظف است اطلاعات درج‌شده (به‌ویژه خطوط ارتباطی و آدرس کارخانه) را به‌روز نگه دارد.</li>
        <li><strong>پرهیز از تصاویر جعلی:</strong> استفاده از عکس‌ها و لوگوهای آماده اینترنتی یا متعلق به دیگران ممنوع است.</li>
        <li><strong>ویرایش آسان:</strong> پس از انتشار، همواره امکان ویرایش اطلاعات از طریق میز کار وجود داشته و نیاز به ثبت مجدد نیست.</li>
        <li><strong>ایندکس موتورهای جستجو:</strong> مشخصات ثبت‌شده مطابق با استانداردهای وب ایندکس شده و از طریق گوگل در دسترس خریداران قرار می‌گیرد.</li>
      </ul>
    </section>

    <hr class="legal-divider">

    <!-- بخش ۲: استانداردهای تصویر -->
    <section id="section-images" class="legal-section">
      <div class="section-title-wrap">
        <span class="sec-num">۲</span>
        <h2>تصویر و لوگوی کسب‌وکار باید چه شرایطی داشته باشد؟</h2>
      </div>
      <p>آمارها نشان می‌دهد شرکت‌های دارای لوگوی واضح و تصاویر واقعی از خط تولید، چندین برابر بیشتر مورد اعتماد خریداران قرار می‌گیرند. تصاویر با شرایط زیر مورد تایید قرار نخواهند گرفت:</p>
      
      <div class="prohibited-grid">
        <div class="prohibit-item">
          <span class="p-icon">❌</span>
          <span>تصاویر بیش از حد تار، کشیده یا بی‌کیفیت</span>
        </div>
        <div class="prohibit-item">
          <span class="p-icon">❌</span>
          <span>تصاویر غیرواقعی و دانلودی از اینترنت</span>
        </div>
        <div class="prohibit-item">
          <span class="p-icon">❌</span>
          <span>تصاویر متعلق به شرکت‌های دیگر در سامانه</span>
        </div>
        <div class="prohibit-item">
          <span class="p-icon">❌</span>
          <span>تصاویر نامرتبط با حوزه کاغذ، چاپ و بسته‌بندی</span>
        </div>
        <div class="prohibit-item">
          <span class="p-icon">❌</span>
          <span>تصاویری که حاوی متن‌های بلند و شلوغ هستند</span>
        </div>
        <div class="prohibit-item">
          <span class="p-icon">❌</span>
          <span>درج قیمت، شماره موبایل یا آدرس سایت روی تصویر</span>
        </div>
      </div>
    </section>

    <hr class="legal-divider">

    <!-- بخش ۳: شرایط استفاده و مشاهده -->
    <section id="section-view" class="legal-section">
      <div class="section-title-wrap">
        <span class="sec-num">۳</span>
        <h2>شرایط استفاده و مشاهده کسب‌وکارها در ۲ کیلو کاغذ</h2>
      </div>
      <ul class="styled-list">
        <li>«۲ کیلو کاغذ» صرفاً بستر ارتباط مستقیم و بی‌واسطه میان خریدار و تولیدکننده است و در روند مذاکرات، قراردادها و مبادلات مالی دخالتی ندارد.</li>
        <li>استفاده از ابزارهای خزش خودکار (Scraping)، استخراج انبوه داده‌ها یا ارسال هرزنامه به شماره‌های ثبت‌شده در سایت پیگرد قانونی دارد.</li>
        <li>کاربران موظفند قبل از هرگونه معامله کلان تجاری، استعلام‌های رسمی و احراز هویت لازم را از طرف مقابل به عمل آورند.</li>
      </ul>
    </section>

    <hr class="legal-divider">

    <!-- بخش ۴: شرایط و قوانین کلی -->
    <section id="section-general" class="legal-section">
      <div class="section-title-wrap">
        <span class="sec-num">۴</span>
        <h2>شرایط و قوانین کلی استفاده از پلتفرم (چارچوب ماده ۱۰ قانون مدنی)</h2>
      </div>
      
      <div class="law-article">
        <h3>ماده ۱ - تعاریف</h3>
        <p><strong>سایت و سامانه:</strong> پلتفرم برخط ۲ کیلو کاغذ به نشانی اینترنتی 2kilopaper.ir که ارائه‌دهنده خدمات بانک اطلاعاتی، دایرکتوری و کاریابی تخصصی است.</p>
        <p><strong>کاربر:</strong> تمامی اشخاص حقیقی یا حقوقی که به عنوان بازدیدکننده، خریدار، کارجو یا ثبت‌کننده در سامانه فعالیت دارند.</p>
        <p><strong>دارایی‌های فکری:</strong> کلیه نام‌ها، نشان‌های تجاری، ساختار دایرکتوری، محتوا، کدها و گرافیک سامانه تحت مالکیت معنوی ۲ کیلو کاغذ قرار دارد.</p>
      </div>

      <div class="law-article">
        <h3>ماده ۲ - شرایط عمومی عضویت</h3>
        <p>۲-۱. داشتن حداقل ۱۸ سال تمام شمسی برای ثبت رسمی شرکت و عقد قراردادهای کاری.</p>
        <p>۲-۲. ثبت شماره تلفن همراه فعال و پاسخگو جهت دریافت کدهای تایید و اطلاع‌رسانی‌ها.</p>
        <p>۲-۳. تبعیت کامل از قوانین جاری جمهوری اسلامی ایران و موازین تجارت الکترونیکی مصوب ۱۳۸۲.</p>
      </div>

      <div class="law-article">
        <h3>ماده ۳ - مسئولیت‌ها و تعهدات طرفین</h3>
        <p>۳-۱. مسئولیت صحت کلیه مشخصات فنی، مجوزها، قیمت‌ها و اطلاعات مندرج در صفحه اختصاصی هر شرکت منحصراً بر عهده خود آن واحد است.</p>
        <p>۳-۲. ۲ کیلو کاغذ تمام تلاش خود را جهت حفظ پایداری و امنیت پلتفرم به کار می‌گیرد، اما مسئولیتی در قبال قطعی‌های سراسری اینترنت کشور ندارد.</p>
      </div>
    </section>

  </div>

</div>

<style>
.legal-page-container {
  width: 100%;
  margin: 24px 0 60px;
  padding: 0;
}
.legal-hero {
  background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #334155 100%);
  color: #ffffff;
  border-radius: 20px;
  padding: 40px 32px 34px;
  text-align: center;
  margin-bottom: 24px;
  box-shadow: 0 10px 30px rgba(15, 23, 42, 0.15);
}
.legal-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 5px 14px;
  background: rgba(255, 255, 255, 0.14);
  backdrop-filter: blur(8px);
  border-radius: 30px;
  font-size: 13px;
  font-weight: 700;
  margin-bottom: 14px;
  border: 1px solid rgba(255, 255, 255, 0.2);
}
.legal-title {
  margin: 0 0 12px;
  font-size: 26px;
  font-weight: 900;
  letter-spacing: -0.4px;
  line-height: 1.4;
}
.legal-desc {
  max-width: 760px;
  margin: 0 auto 18px;
  font-size: 14px;
  line-height: 1.8;
  color: #cbd5e1;
}
.legal-date {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 12.5px;
  color: #94a3b8;
  background: rgba(0, 0, 0, 0.25);
  padding: 4px 14px;
  border-radius: 20px;
}

.legal-nav-pills {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  justify-content: center;
  margin-bottom: 28px;
}
.pill-link {
  background: #ffffff;
  border: 1px solid #cbd5e1;
  color: #334155;
  padding: 8px 16px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 700;
  text-decoration: none;
  transition: all 0.2s ease;
}
.pill-link:hover {
  background: var(--primary);
  color: #ffffff;
  border-color: var(--primary);
}

.legal-content-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 20px;
  padding: 36px 36px 40px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
}
.legal-section {
  padding: 10px 0;
}
.section-title-wrap {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 16px;
}
.sec-num {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  background: #eff6ff;
  color: #1d4ed8;
  border: 1px solid #bfdbfe;
  font-size: 15px;
  font-weight: 900;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.section-title-wrap h2 {
  margin: 0;
  font-size: 20px;
  font-weight: 900;
  color: #0f172a;
}
.legal-section p {
  color: #475569;
  font-size: 14.5px;
  line-height: 1.85;
  margin: 0 0 16px;
}

.styled-list {
  margin: 0 0 16px 20px;
  padding: 0;
  color: #334155;
  font-size: 14px;
  line-height: 1.85;
}
.styled-list li {
  margin-bottom: 10px;
}
.styled-list strong {
  color: #0f172a;
}

.prohibited-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
  gap: 12px;
  margin-top: 14px;
}
.prohibit-item {
  display: flex;
  align-items: center;
  gap: 10px;
  background: #fef2f2;
  border: 1px solid #fecaca;
  padding: 12px 14px;
  border-radius: 10px;
  font-size: 13px;
  color: #991b1b;
  font-weight: 600;
}
.p-icon {
  font-size: 14px;
  flex-shrink: 0;
}

.legal-divider {
  border: none;
  height: 1px;
  background: #f1f5f9;
  margin: 32px 0;
}

.law-article {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 18px 20px;
  margin-bottom: 16px;
}
.law-article h3 {
  margin: 0 0 10px;
  font-size: 16px;
  font-weight: 800;
  color: #1e293b;
}
.law-article p {
  margin: 0 0 8px;
  font-size: 13.5px;
  color: #475569;
  line-height: 1.8;
}
.law-article p:last-child {
  margin-bottom: 0;
}

@media (max-width: 640px) {
  .legal-hero {
    padding: 28px 18px 24px;
  }
  .legal-title {
    font-size: 20px;
  }
  .legal-content-card {
    padding: 24px 18px;
  }
  .section-title-wrap h2 {
    font-size: 17px;
  }
  .prohibited-grid {
    grid-template-columns: 1fr;
  }
}
</style>
@endsection
