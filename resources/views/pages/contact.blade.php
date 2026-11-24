@extends('layouts.app', [
  'title' => 'تماس با ما',
  'description' => 'راه‌های ارتباطی، آدرس دفتر مرکزی، شماره تماس پشتیبانی و فرم ارسال پیام به ۲ کیلو کاغذ'
])

@section('content')
<div class="contact-page-container">

  <!-- هدر صفحه تماس با ما -->
  <div class="contact-hero">
    <div class="contact-badge">
      <span>✉️</span>
      <span>صدای شما شنیده می‌شود</span>
    </div>
    <h1 class="contact-hero-title">راه‌های ارتباط با ۲ کیلو کاغذ</h1>
    <p class="contact-hero-desc">
      اگر انتقاد یا پیشنهادی در خصوص بهتر شدن خدمات ۲ کیلو کاغذ دارید، یا به دنبال مشاوره و راهنمایی هستید، حتماً با ما در میان بگذارید. حتی اگر احساس می‌کنید پیام‌تان کوتاه یا ساده است، نکته شما می‌تواند به پیشرفت این صنعت کمک کند.
    </p>
  </div>

  <div class="contact-grid">

    <!-- ستون سمت راست: راه‌های ارتباطی مستقیم -->
    <div class="contact-info-col">

      <!-- کارت اطلاعات تماس تلفنی و ایمیل -->
      <div class="info-card">
        <h2 class="info-card-title">ارتباط مستقیم و پشتیبانی تلفنی</h2>
        
        <div class="contact-method-item">
          <div class="method-icon-wrap phone-bg">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
          </div>
          <div class="method-details">
            <span class="m-label">تلفن پشتیبانی و مشاوره:</span>
            <a href="tel:02128421075" class="m-value ltr-val">۰۲۱-۲۸۴۲۱۰۷۵</a>
            <span class="m-hint">پاسخگویی در روزهای کاری از ۹ صبح تا ۱۷ عصر</span>
          </div>
        </div>

        <div class="contact-method-item">
          <div class="method-icon-wrap email-bg">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
          </div>
          <div class="method-details">
            <span class="m-label">پست الکترونیکی رسمی:</span>
            <a href="mailto:support@2kilopaper.ir" class="m-value ltr-val">support@2kilopaper.ir</a>
            <span class="m-hint">پاسخگویی به درخواست‌های رسمی و مکاتبات</span>
          </div>
        </div>

        <div class="contact-method-item">
          <div class="method-icon-wrap address-bg">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
          </div>
          <div class="method-details">
            <span class="m-label">دفتر مرکزی ۲ کیلو کاغذ:</span>
            <p class="m-value address-p">تهران، خیابان مطهری، خیابان میرعماد، پلاک ۱۱، طبقه دوم، واحد ۱۲</p>
          </div>
        </div>
      </div>

      <!-- کارت پیام‌رسان‌ها و شبکه‌های اجتماعی -->
      <div class="info-card">
        <h3 class="social-box-title">پیام‌رسان‌ها و ارتباط آنلاین</h3>
        <p class="social-sub">برای پاسخگویی سریع‌تر می‌توانید در پیام‌رسان‌های زیر مستقیماً با پشتیبانی در ارتباط باشید:</p>
        
        <div class="social-channels-grid">
          
          <!-- بله -->
          <a href="https://ble.ir/dokilopaper" target="_blank" rel="noopener" class="channel-pill channel-bale">
            <span class="ch-badge">پیشنهادی</span>
            <span class="ch-icon">💬</span>
            <div class="ch-info">
              <strong>پیام‌رسان بله</strong>
              <span>ble.ir/dokilopaper</span>
            </div>
            <span class="ch-arrow">←</span>
          </a>

          <!-- تلگرام -->
          <a href="https://t.me/dokilopaper" target="_blank" rel="noopener" class="channel-pill channel-tg">
            <span class="ch-icon">✈️</span>
            <div class="ch-info">
              <strong>کانال و پشتیبانی تلگرام</strong>
              <span>t.me/dokilopaper</span>
            </div>
            <span class="ch-arrow">←</span>
          </a>

          <!-- اینستاگرام -->
          <a href="https://instagram.com/2kilopaper" target="_blank" rel="noopener" class="channel-pill channel-ig">
            <span class="ch-icon">📷</span>
            <div class="ch-info">
              <strong>صفحه رسمی اینستاگرام</strong>
              <span>@2kilopaper</span>
            </div>
            <span class="ch-arrow">←</span>
          </a>

        </div>
      </div>

    </div>

    <!-- ستون سمت چپ: فرم ارسال پیام -->
    <div class="contact-form-col">
      <div class="form-card">
        <h2 class="form-title">ارسال پیام یا پیشنهاد</h2>
        <p class="form-desc">پیام شما مستقیماً به دست کارشناسان پشتیبانی ۲ کیلو کاغذ خواهد رسید.</p>

        @if ($errors->any())
          <div class="alert-box error">
            <ul style="margin:0;padding-right:18px">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @if (session('ok'))
          <div class="alert-box success">
            <span>✓</span>
            <span>{{ session('ok') }}</span>
          </div>
        @endif

        <form action="{{ route('pages.contact.submit') }}" method="POST" class="styled-contact-form">
          @csrf
          
          <div class="input-group">
            <label for="nameInput">نام و نام خانوادگی <span class="req">*</span></label>
            <input type="text" id="nameInput" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" placeholder="مثال: علی رضایی" required>
          </div>

          <div class="form-dual-row">
            <div class="input-group">
              <label for="phoneInput">شماره تماس (موبایل) <span class="req">*</span></label>
              <input type="tel" id="phoneInput" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}" placeholder="۰۹۱۲۳۴۵۶۷۸۹" required dir="ltr">
            </div>

            <div class="input-group">
              <label for="emailInput">آدرس ایمیل <span class="optional">(اختیاری)</span></label>
              <input type="email" id="emailInput" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" placeholder="info@example.com" dir="ltr">
            </div>
          </div>

          <div class="input-group">
            <label for="subjectInput">موضوع پیام</label>
            <input type="text" id="subjectInput" name="subject" value="{{ old('subject') }}" placeholder="مثال: پیشنهاد همکاری، استعلام تبلیغات، راهنمایی...">
          </div>

          <div class="input-group">
            <label for="messageInput">متن پیام یا انتقاد <span class="req">*</span></label>
            <textarea id="messageInput" name="message" rows="5" placeholder="پیام، نظر یا پیشنهاد خود را به طور کامل بنویسید..." required>{{ old('message') }}</textarea>
          </div>

          <button type="submit" class="submit-form-btn">
            <span>ارسال پیام</span>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
          </button>
        </form>

      </div>
    </div>

  </div>

</div>

<style>
.contact-page-container {
  width: 100%;
  margin: 24px 0 60px;
  padding: 0;
}
.contact-hero {
  background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #0284c7 100%);
  color: #ffffff;
  border-radius: 20px;
  padding: 42px 36px 36px;
  text-align: center;
  margin-bottom: 32px;
  box-shadow: 0 10px 30px rgba(30, 58, 138, 0.16);
}
.contact-badge {
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
.contact-hero-title {
  margin: 0 0 12px;
  font-size: 28px;
  font-weight: 900;
  letter-spacing: -0.4px;
}
.contact-hero-desc {
  max-width: 740px;
  margin: 0 auto;
  font-size: 14.5px;
  line-height: 1.85;
  color: #e0f2fe;
}

.contact-grid {
  display: grid;
  grid-template-columns: 1fr 1.15fr;
  gap: 28px;
  align-items: flex-start;
}
.contact-info-col {
  display: flex;
  flex-direction: column;
  gap: 20px;
}
.info-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 18px;
  padding: 28px 24px;
  box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
}
.info-card-title {
  margin: 0 0 20px;
  font-size: 17px;
  font-weight: 900;
  color: #0f172a;
}
.contact-method-item {
  display: flex;
  align-items: flex-start;
  gap: 14px;
  margin-bottom: 18px;
  padding-bottom: 18px;
  border-bottom: 1px dashed #f1f5f9;
}
.contact-method-item:last-child {
  margin-bottom: 0;
  padding-bottom: 0;
  border-bottom: none;
}
.method-icon-wrap {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.phone-bg { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.email-bg { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
.address-bg { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }

.method-details {
  display: flex;
  flex-direction: column;
  gap: 2px;
}
.m-label {
  font-size: 12px;
  font-weight: 700;
  color: #64748b;
}
.m-value {
  font-size: 15px;
  font-weight: 900;
  color: #0f172a;
  text-decoration: none;
  transition: color 0.2s;
}
.m-value:hover {
  color: var(--primary);
}
.ltr-val {
  direction: ltr;
  text-align: right;
  display: inline-block;
}
.address-p {
  margin: 0;
  font-size: 13.5px;
  font-weight: 600;
  line-height: 1.6;
  color: #334155;
}
.m-hint {
  font-size: 11.5px;
  color: #94a3b8;
  margin-top: 2px;
}

.social-box-title {
  margin: 0 0 6px;
  font-size: 16px;
  font-weight: 900;
  color: #0f172a;
}
.social-sub {
  margin: 0 0 16px;
  font-size: 12.5px;
  color: #64748b;
  line-height: 1.6;
}
.social-channels-grid {
  display: flex;
  flex-direction: column;
  gap: 10px;
}
.channel-pill {
  display: flex;
  align-items: center;
  gap: 12px;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  padding: 12px 14px;
  border-radius: 12px;
  text-decoration: none;
  transition: all 0.2s ease;
  position: relative;
}
.channel-pill:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  border-color: #cbd5e1;
}
.channel-bale:hover { background: #f0fdf4; border-color: #86efac; }
.channel-tg:hover { background: #f0f9ff; border-color: #7dd3fc; }
.channel-ig:hover { background: #fdf2f8; border-color: #f472b6; }

.ch-badge {
  position: absolute;
  left: 36px;
  top: 12px;
  background: #dcfce7;
  color: #166534;
  border: 1px solid #bbf7d0;
  font-size: 10px;
  font-weight: 800;
  padding: 1px 7px;
  border-radius: 10px;
}
.ch-icon {
  font-size: 22px;
  flex-shrink: 0;
}
.ch-info strong {
  display: block;
  font-size: 13.5px;
  font-weight: 800;
  color: #0f172a;
}
.ch-info span {
  display: block;
  font-size: 11.5px;
  color: #64748b;
  direction: ltr;
  text-align: right;
}
.ch-arrow {
  margin-right: auto;
  font-size: 16px;
  color: #94a3b8;
}

.form-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 18px;
  padding: 32px 30px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
}
.form-title {
  margin: 0 0 6px;
  font-size: 20px;
  font-weight: 900;
  color: #0f172a;
}
.form-desc {
  margin: 0 0 22px;
  font-size: 13.5px;
  color: #64748b;
}

.alert-box {
  padding: 12px 16px;
  border-radius: 10px;
  margin-bottom: 20px;
  font-size: 13.5px;
  line-height: 1.6;
}
.alert-box.success {
  background: #ecfdf5;
  color: #065f46;
  border: 1px solid #a7f3d0;
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 700;
}
.alert-box.error {
  background: #fef2f2;
  color: #991b1b;
  border: 1px solid #fecaca;
}

.styled-contact-form {
  display: flex;
  flex-direction: column;
  gap: 16px;
}
.input-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
}
.input-group label {
  font-size: 13px;
  font-weight: 700;
  color: #334155;
}
.req { color: #dc2626; }
.optional { color: #94a3b8; font-weight: normal; font-size: 11.5px; }

.input-group input, .input-group textarea {
  border: 1.5px solid #cbd5e1;
  border-radius: 10px;
  padding: 10px 14px;
  font-size: 14px;
  font-family: inherit;
  color: #0f172a;
  outline: none;
  transition: all 0.2s ease;
  background: #ffffff;
}
.input-group input:focus, .input-group textarea:focus {
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}
.form-dual-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 14px;
}

.submit-form-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  background: var(--primary);
  color: #ffffff;
  padding: 12px 24px;
  border: none;
  border-radius: 10px;
  font-size: 15px;
  font-weight: 800;
  font-family: inherit;
  cursor: pointer;
  transition: all 0.2s ease;
  margin-top: 4px;
}
.submit-form-btn:hover {
  background: var(--primary-d);
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(31, 95, 168, 0.25);
}

@media (max-width: 860px) {
  .contact-grid {
    grid-template-columns: 1fr;
  }
}
@media (max-width: 640px) {
  .contact-hero {
    padding: 28px 18px 24px;
  }
  .contact-hero-title {
    font-size: 21px;
  }
  .form-dual-row {
    grid-template-columns: 1fr;
  }
  .form-card {
    padding: 24px 18px;
  }
}
</style>
@endsection
