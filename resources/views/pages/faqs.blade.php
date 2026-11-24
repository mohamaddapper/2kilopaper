@extends('layouts.app', [
 'title' => 'سوالات متداول',
 'description' => 'پاسخ به پرتکرارترین پرسش‌های کاربران درباره ثبت شرکت، کاریابی، تعرفه‌ها و خدمات ۲ کیلو کاغذ'
])

@section('content')
<div class="faq-container">

  <!-- بنر بالای صفحه -->
  <div class="faq-hero">
    <div class="faq-hero-badge">
      <span>{!! svg_icon('help', 15) !!}</span>
      <span>مرکز پاسخ به پرسش‌ها</span>
    </div>
    <h1 class="faq-hero-title">سوالات متداول کاربران</h1>
    <p class="faq-hero-desc">
      پاسخ مهم‌ترین و پرتکرارترین پرسش‌های مطرح‌شده توسط مدیران، کارفرمایان و کارجویان صنعت کاغذ، چاپ و بسته‌بندی را در اینجا مطالعه فرمایید.
    </p>

    <!-- کادر جستجوی زنده در سوالات -->
    <div class="faq-search-box">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
      <input type="text" id="faqSearchInput" placeholder="جستجو در متن یا موضوع سوالات متداول..." oninput="filterFaqs()">
    </div>
  </div>

  <!-- لیست آکاردئونی سوالات -->
  <div class="faq-list" id="faqList">

    <!-- سوال ۱ -->
    <div class="faq-item" data-keywords="۲ کیلو کاغذ چیست معرفی پلتفرم دایرکتوری چیست هدف">
      <button type="button" class="faq-question" onclick="toggleFaq(this)">
        <span class="q-title">۲ کیلو کاغذ چیست و چه کاربردی دارد؟</span>
        <span class="q-icon">+</span>
      </button>
      <div class="faq-answer">
        <p>
          «۲ کیلو کاغذ» مرجع تخصصی و دایرکتوری جامع صنعت کاغذ، کارتن، مقوا، چاپ و بسته‌بندی ایران است. هدف ما گردآوری و دسته‌بندی ساختاریافته تمامی تولیدکنندگان مواد اولیه، واردکنندگان، چاپخانه‌ها، شرکت‌های بسته‌بندی، تامین‌کنندگان ماشین‌آلات و خدمات پس از چاپ در سطح کشور است تا خریداران و تولیدکنندگان به راحتی بتوانند شرکای تجاری، تامین‌کنندگان و فرصت‌های شغلی مورد نظر خود را بر اساس تخصص و استان پیدا کنند.
        </p>
      </div>
    </div>

    <!-- سوال ۲ -->
    <div class="faq-item" data-keywords="چگونه کسب و کارم را ثبت کنم مراحل ثبت شرکت نحوه درج اطلاعات راهنما">
      <button type="button" class="faq-question" onclick="toggleFaq(this)">
        <span class="q-title">چگونه کسب‌وکار یا کارگاه خود را در ۲ کیلو کاغذ ثبت کنم؟</span>
        <span class="q-icon">+</span>
      </button>
      <div class="faq-answer">
        <p>
          برای ثبت اطلاعات شرکت یا کارگاه خود، کافی است در بالای سایت بر روی دکمه <strong>«ورود / ثبت‌نام»</strong> کلیک کرده و با شماره موبایل خود وارد شوید. سپس در بخش میز کار (یا دکمه <strong>ثبت شرکت</strong>)، فرم ثبت مشخصات شامل نام، زمینه فعالیت، موقعیت جغرافیایی، لوگو، تصاویر و راه‌های ارتباطی را تکمیل نمایید تا پس از بررسی تیم نظارت در دایرکتوری منتشر گردد.
        </p>
      </div>
    </div>

    <!-- سوال ۳ -->
    <div class="faq-item" data-keywords="چرا کسب و کار من حذف شده است دلایل حذف اطلاعات فیک عکس اینترنتی">
      <button type="button" class="faq-question" onclick="toggleFaq(this)">
        <span class="q-title">چرا ممکن است یک کسب‌وکار پس از ثبت حذف یا تایید نشود؟</span>
        <span class="q-icon">+</span>
      </button>
      <div class="faq-answer">
        <p>
          کسب‌وکارهای ثبت‌شده در صورت نقض قوانین پلتفرم ممکن است از انتشار باز بمانند. دلایل رایج عبارتند از:
        </p>
        <ul>
          <li>ثبت اطلاعات نامعتبر، ناقص یا شماره‌های تماس غیرپاسخگو</li>
          <li>استفاده از لوگوها و عکس‌های اینترنتی نامربوط یا مغایر با عرف و قوانین</li>
          <li>ثبت فعالیت‌هایی که در حوزه صنایع کاغذ، چاپ، بسته‌بندی و سلولزی قرار ندارند</li>
          <li>درج متن‌های تبلیغاتی حاوی اطلاعات گمراه‌کننده یا لینک‌های مخرب</li>
        </ul>
      </div>
    </div>

    <!-- سوال ۴ -->
    <div class="faq-item" data-keywords="آیا برای ثبت نام باید هزینه ای پرداخت کنم تعرفه قیمت رایگان رزومه کارجو">
      <button type="button" class="faq-question" onclick="toggleFaq(this)">
        <span class="q-title">آیا برای ثبت‌نام و استفاده از خدمات باید هزینه‌ای پرداخت کنم؟</span>
        <span class="q-icon">+</span>
      </button>
      <div class="faq-answer">
        <p>
          اگر به عنوان <strong>کارجو</strong> فعالیت می‌کنید، ثبت رزومه و مشاهده فرصت‌های شغلی کاملاً رایگان است. همچنین جستجو در دایرکتوری شرکت‌ها برای عموم خریداران و تولیدکنندگان رایگان می‌باشد. برای شرکت‌هایی که تمایل به دریافت نشان ویژه (طلایی، نقره‌ای، برنزی) و قرارگیری در اولویت نتایج جستجو دارند، تعرفه‌های ویژه‌سازی در صفحه <a href="{{ route('subscription.fee') }}">تعرفه‌های اشتراک</a> قابل مشاهده است.
        </p>
      </div>
    </div>

    <!-- سوال ۵ -->
    <div class="faq-item" data-keywords="دسته بندی کسب و کار من در سایت وجود ندارد افزودن رسته جدید صنف">
      <button type="button" class="faq-question" onclick="toggleFaq(this)">
        <span class="q-title">دسته‌بندی مرتبط با زمینه فعالیت شرکت من در سایت وجود ندارد!</span>
        <span class="q-icon">+</span>
      </button>
      <div class="faq-answer">
        <p>
          دسته‌بندی‌های ۲ کیلو کاغذ به صورت مداوم بر اساس شاخه‌های جدید صنعت بازنگری و توسعه می‌یابند. چنانچه زمینه فعالیت تخصصی شما در لیست دسته‌ها موجود نیست، می‌توانید هنگام ثبت، نزدیک‌ترین صنف را انتخاب کرده و از طریق صفحه <a href="{{ route('pages.contact') }}">تماس با ما</a>، پیشنهاد افزودن دسته جدید را برای تیم فنی ارسال نمایید.
        </p>
      </div>
    </div>

    <!-- سوال ۶ -->
    <div class="faq-item" data-keywords="چگونه اعلانات ۲ کیلو کاغذ را لغو کنم پیام نوتیفیکیشن مرورگر غیرفعال">
      <button type="button" class="faq-question" onclick="toggleFaq(this)">
        <span class="q-title">چگونه اعلانات و پیام‌های سایت را مدیریت یا لغو کنم؟</span>
        <span class="q-icon">+</span>
      </button>
      <div class="faq-answer">
        <p>
          برای مدیریت یا لغو اعلانات مرورگر (Notifications):
        </p>
        <ol>
          <li>در مرورگر خود (مانند Google Chrome)، علامت قفل کنار آدرس سایت را بزنید.</li>
          <li>در منوی بازشده گزینه Permissions (مجوزها) را انتخاب کنید.</li>
          <li>گزینه Notifications را بر روی حالت Block یا Reset قرار دهید.</li>
        </ol>
      </div>
    </div>

    <!-- سوال ۷ -->
    <div class="faq-item" data-keywords="چه مدارکی برای ثبت نام در ۲ کیلو کاغذ لازم است مجوز پروانه بهره برداری">
      <button type="button" class="faq-question" onclick="toggleFaq(this)">
        <span class="q-title">چه مدارکی برای ثبت‌نام اولیه در ۲ کیلو کاغذ لازم است؟</span>
        <span class="q-icon">+</span>
      </button>
      <div class="faq-answer">
        <p>
          برای ثبت‌نام و ثبت اولیه اطلاعات شرکت، نیاز به ارائه فیزیکی مدارک نیست و ثبت‌نام با یک شماره موبایل معتبر انجام می‌شود. با این حال، مسئولیت صحت کلیه اطلاعات ثبت‌شده (پروانه کسب، نام ثبتی، آدرس کارخانه و شماره‌های تماس) بر عهده ثبت‌کننده بوده و در صورت گزارش تخلف، احراز مدارک از کارفرما مطالبه خواهد شد.
        </p>
      </div>
    </div>

    <!-- سوال ۸ -->
    <div class="faq-item" data-keywords="چه کسب و کارهایی نمی توانند در ۲ کیلو کاغذ ثبت نام کنند غیرمجاز ممنوع">
      <button type="button" class="faq-question" onclick="toggleFaq(this)">
        <span class="q-title">چه نوع کسب‌وکارهایی مجاز به ثبت در پلتفرم نیستند؟</span>
        <span class="q-icon">+</span>
      </button>
      <div class="faq-answer">
        <p>
          کسب‌وکارهای غیرمرتبط با زنجیره تامین کاغذ، چاپ، کارتن، مقوا و بسته‌بندی، کسب‌وکارهای فاقد هویت مشخص، واسطه‌گری‌های بدون مجوز، یا محصولاتی که مغایر با قوانین جمهوری اسلامی ایران باشند، مجاز به ثبت در ۲ کیلو کاغذ نبوده و حساب آن‌ها مسدود خواهد شد. برای مطالعه جزییات بیشتر به صفحه <a href="{{ route('pages.terms') }}">شرایط و قوانین کلی</a> مراجعه فرمایید.
        </p>
      </div>
    </div>

    <!-- سوال ۹ -->
    <div class="faq-item" data-keywords="نحوه حذف کسب و کار به چه صورت است خروج از سایت پاک کردن اطلاعات">
      <button type="button" class="faq-question" onclick="toggleFaq(this)">
        <span class="q-title">نحوه و شرایط حذف کسب‌وکار به چه صورت است؟</span>
        <span class="q-icon">+</span>
      </button>
      <div class="faq-answer">
        <p>
          جهت حفظ شفافیت، سوابق اعتباری و جلوگیری از سوءاستفاده‌های احتمالی، حذف مستقیم کسب‌وکار توسط کاربر در پنل کاربری انجام نمی‌شود. در صورت تغییر فعالیت، انتقال مالکیت یا تعطیلی مجموعه، مدیر مربوطه می‌تواند درخواست کتبی خود را همراه با مستندات از طریق صفحه <a href="{{ route('pages.contact') }}">تماس با ما</a> ارسال نماید تا کارشناسان پشتیبانی اقدام لازم را به عمل آورند.
        </p>
      </div>
    </div>

  </div>

  <!-- کادر نیافتن پاسخ -->
  <div class="faq-support-box">
    <div class="support-icon">{!! svg_icon('message', 15) !!}</div>
    <div class="support-text">
      <strong>پاسخ سوال خود را در این لیست پیدا نکردید؟</strong>
      <span>کارشناسان پشتیبانی ۲ کیلو کاغذ همه‌روزه آماده راهنمایی و پاسخگویی به سوالات شما هستند.</span>
    </div>
    <a href="{{ route('pages.contact') }}" class="btn support-btn">
      <span>ارتباط با پشتیبانی</span>
      <span>←</span>
    </a>
  </div>

</div>

<style>
.faq-container {
  width: 100%;
  margin: 24px 0 60px;
  padding: 0;
}
.faq-hero {
  background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #0284c7 100%);
  color: #ffffff;
  border-radius: 20px;
  padding: 40px 32px 34px;
  text-align: center;
  box-shadow: 0 10px 30px rgba(30, 64, 175, 0.16);
  margin-bottom: 28px;
}
.faq-hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 5px 14px;
  background: rgba(255, 255, 255, 0.18);
  backdrop-filter: blur(8px);
  border-radius: 30px;
  font-size: 13px;
  font-weight: 700;
  margin-bottom: 14px;
  border: 1px solid rgba(255, 255, 255, 0.25);
}
.faq-hero-title {
  margin: 0 0 10px;
  font-size: 27px;
  font-weight: 900;
  letter-spacing: -0.4px;
}
.faq-hero-desc {
  max-width: 660px;
  margin: 0 auto 24px;
  font-size: 14px;
  line-height: 1.8;
  color: #e0f2fe;
}
.faq-search-box {
  display: flex;
  align-items: center;
  gap: 12px;
  background: #ffffff;
  border-radius: 14px;
  padding: 12px 18px;
  max-width: 600px;
  margin: 0 auto;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
  color: #64748b;
}
.faq-search-box input {
  flex: 1;
  border: none;
  outline: none;
  font-size: 14.5px;
  font-family: inherit;
  color: #0f172a;
}

.faq-list {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-bottom: 32px;
}
.faq-item {
  background: #ffffff;
  border: 1.5px solid #e2e8f0;
  border-radius: 14px;
  overflow: hidden;
  transition: all 0.2s ease;
}
.faq-item:hover {
  border-color: #cbd5e1;
}
.faq-item.active {
  border-color: #3b82f6;
  box-shadow: 0 4px 16px rgba(59, 130, 246, 0.08);
}
.faq-question {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 18px 20px;
  background: transparent;
  border: none;
  cursor: pointer;
  text-align: right;
  font-family: inherit;
}
.q-title {
  font-size: 15px;
  font-weight: 800;
  color: #0f172a;
  line-height: 1.5;
  transition: color 0.2s;
}
.faq-item.active .q-title {
  color: #1d4ed8;
}
.q-icon {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background: #f1f5f9;
  color: #475569;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
  font-weight: 800;
  flex-shrink: 0;
  margin-right: 12px;
  transition: all 0.25s ease;
}
.faq-item.active .q-icon {
  transform: rotate(45deg);
  background: #1d4ed8;
  color: #ffffff;
}

.faq-answer {
  display: none;
  padding: 0 20px 20px;
  color: #475569;
  font-size: 14px;
  line-height: 1.85;
  border-top: 1px dashed #f1f5f9;
}
.faq-item.active .faq-answer {
  display: block;
  padding-top: 14px;
}
.faq-answer p { margin: 0 0 10px; }
.faq-answer p:last-child { margin-bottom: 0; }
.faq-answer ul, .faq-answer ol {
  margin: 6px 0 12px 20px;
  padding: 0;
}
.faq-answer li {
  margin-bottom: 6px;
}
.faq-answer a {
  color: var(--primary);
  font-weight: 700;
}

.faq-support-box {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  padding: 24px 28px;
  display: flex;
  align-items: center;
  gap: 20px;
  flex-wrap: wrap;
}
.support-icon {
  font-size: 36px;
  flex-shrink: 0;
}
.support-text {
  flex: 1;
  min-width: 240px;
}
.support-text strong {
  display: block;
  font-size: 15.5px;
  color: #0f172a;
  margin-bottom: 4px;
}
.support-text span {
  font-size: 13px;
  color: #64748b;
  line-height: 1.6;
}
.support-btn {
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

@media (max-width: 640px) {
  .faq-hero {
    padding: 28px 18px 24px;
  }
  .faq-hero-title {
    font-size: 21px;
  }
  .q-title {
    font-size: 14px;
  }
  .faq-support-box {
    flex-direction: column;
    text-align: center;
    padding: 20px;
  }
  .support-btn {
    width: 100%;
    justify-content: center;
  }
}
</style>

<script>
function toggleFaq(btn) {
 const item = btn.closest('.faq-item');
 const wasActive = item.classList.contains('active');
 
 // بستن سایر آیتم‌ها
 document.querySelectorAll('.faq-item').forEach(el => el.classList.remove('active'));

 if (!wasActive) {
 item.classList.add('active');
 }
}

function filterFaqs() {
 const query = document.getElementById('faqSearchInput').value.trim().toLowerCase();
 const items = document.querySelectorAll('.faq-item');
 items.forEach(item => {
 const text = (item.getAttribute('data-keywords') + ' ' + item.textContent).toLowerCase();
 if (!query || text.includes(query)) {
 item.style.display = 'block';
 } else {
 item.style.display = 'none';
 }
 });
}
</script>
@endsection
