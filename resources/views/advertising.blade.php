@extends('layouts.app', [
 'title' => 'تعرفه تبلیغات و پلن‌های ویژه | ۲ کیلو کاغذ',
 'description' => 'پلن‌های ویژه طلایی، نقره‌ای، برنزی و جایگاه‌های بنری تبلیغات در مرکز صنعت کاغذ، چاپ و بسته‌بندی ایران'
])

@section('content')
<div class="page-wrapper" style="margin:24px 0 60px">

  <!-- بنر هدر و معرفی -->
  <div style="text-align:center;padding:36px 20px;background:#fff;border:1px solid var(--border);border-radius:16px;margin-bottom:32px;box-shadow:0 2px 10px rgba(0,0,0,0.02)">
    <span class="badge" style="background:#fef3c7;color:#b45309;font-size:13px;padding:4px 14px;font-weight:700;margin-bottom:12px;display:inline-block">
      {!! svg_icon('megaphone', 15) !!} معرفی بسته‌های تبلیغات و برندینگ B2B
    </span>
    <h1 style="margin:0 0 12px;font-size:26px;color:var(--text);line-height:1.5">
      تبلیغات هدفمند در مرکز صنعت کاغذ، چاپ و بسته‌بندی ایران
    </h1>
    <p style="margin:0 auto;max-width:800px;color:var(--muted);font-size:15px;line-height:1.8">
      ۲ کیلو کاغذ دروازه ارتباطی میان صدها تأمین‌کننده، چاپخانه، کارتن‌ساز و ماشین‌ساز صنعتی است. مشتریانی که وارد این پلتفرم می‌شوند، در حال تصمیم‌گیری و خرید هستند؛ حضور پررنگ‌تر در این فضا به‌طور مستقیم روی فروش و اعتبار برند شما تأثیر می‌گذارد.
    </p>
  </div>

  <!-- مزایای تبلیغات در سایت -->
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:16px;margin-bottom:36px">
    
    <div class="card" style="padding:20px;background:#fff;border-radius:12px;border:1px solid var(--border)">
      <h3 style="margin:0 0 8px;font-size:16px;color:var(--text);display:flex;align-items:center;gap:8px">
        <span>{!! svg_icon('target', 15) !!}</span> مخاطبان ۱۰۰٪ هدفمند
      </h3>
      <p style="margin:0;font-size:13px;color:var(--muted);line-height:1.7">
        بازدیدکنندگان سایت، مدیران خرید و فعالان صنعتی هستند که دقیقاً به‌دنبال محصول یا خدمات شما می‌گردند، نه مخاطبان متفرقه.
      </p>
    </div>

    <div class="card" style="padding:20px;background:#fff;border-radius:12px;border:1px solid var(--border)">
      <h3 style="margin:0 0 8px;font-size:16px;color:var(--text);display:flex;align-items:center;gap:8px">
        <span>{!! svg_icon('star-filled', 15) !!}</span> جایگاه‌های ویژه (Featured)
      </h3>
      <p style="margin:0;font-size:13px;color:var(--muted);line-height:1.7">
        در صفحات دسته‌بندی، نتایج جستجو و صفحه اصلی، جایگاه‌های طلایی باعث می‌شوند نام شما چندین برابر بیشتر از رقبا دیده شود.
      </p>
    </div>

    <div class="card" style="padding:20px;background:#fff;border-radius:12px;border:1px solid var(--border)">
      <h3 style="margin:0 0 8px;font-size:16px;color:var(--text);display:flex;align-items:center;gap:8px">
        <span>{!! svg_icon('phone', 15) !!}</span> افزایش تماس مستقیم و ورودی B2B
      </h3>
      <p style="margin:0;font-size:13px;color:var(--muted);line-height:1.7">
        جایگاه‌های تبلیغاتی تا چندین برابر بیش از حالت عادی، کلیک روی شماره تماس مستقیم، واتساپ و کاتالوگ شما ایجاد می‌کنند.
      </p>
    </div>

  </div>

  <!-- بخش اصلی: پلن‌های ویژه شرکت‌ها (طلایی، نقره‌ای، برنزی) -->
  <div style="background:#fff;border:1px solid var(--border);border-radius:16px;padding:28px;margin-bottom:36px">
    <div style="text-align:center;margin-bottom:28px">
      <h2 style="margin:0 0 8px;font-size:22px;color:var(--text)">
        {!! svg_icon('star-filled', 15) !!} پلن‌های ویژه شرکت‌ها (طلایی، نقره‌ای و برنزی)
      </h2>
      <p style="margin:0;font-size:14px;color:var(--muted)">
        شرکت‌های ویژه با برچسب شاخص بر روی عکس، در ابتدای دسته‌بندی‌ها و در بخش شرکت‌های ویژه صفحه اصلی نمایش داده می‌شوند.
      </p>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px">
      
      <!-- پلن برنزی -->
      <div class="card" style="padding:24px;background:#fff;border-radius:14px;border:1.5px solid #cbd5e1;display:flex;flex-direction:column;justify-content:space-between">
        <div>
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
            <span class="badge bronze" style="font-size:12px;padding:4px 10px">{!! svg_icon('award', 15) !!} سطح ۳</span>
            <span style="font-size:12px;color:var(--muted)">پایه ماهانه: ۱,۰۰۰,۰۰۰ تومان</span>
          </div>
          <h3 style="margin:0 0 14px;font-size:18px;color:#78350f">ویژه برنزی</h3>
          <p style="font-size:13px;color:var(--muted);line-height:1.6;margin-bottom:16px">
            حضور در بخش شرکت‌های ویژه و اولویت بالاتر از تمام شرکت‌های عادی دایرکتوری.
          </p>

          <div style="background:#f8fafc;padding:12px;border-radius:8px;font-size:13px;margin-bottom:16px">
            <div style="display:flex;justify-content:space-between;padding:4px 0"><span>۱ ماهه:</span> <b>۱/۰۰۰/۰۰۰ تومان</b></div>
            <div style="display:flex;justify-content:space-between;padding:4px 0"><span>۳ ماهه (۱۰٪ تخفیف):</span> <b>۲/۷۰۰/۰۰۰ تومان</b></div>
            <div style="display:flex;justify-content:space-between;padding:4px 0"><span>۶ ماهه (۲۰٪ تخفیف):</span> <b>۴/۸۰۰/۰۰۰ تومان</b></div>
            <div style="display:flex;justify-content:space-between;padding:4px 0;color:#16a34a;font-weight:700"><span>۱ ساله (۳۰٪ تخفیف):</span> <b>۸/۴۰۰/۰۰۰ تومان</b></div>
          </div>
        </div>

        <a href="#order_section" class="btn ghost" style="width:100%;text-align:center;box-sizing:border-box">سفارش پلن برنزی</a>
      </div>

      <!-- پلن نقره‌ای -->
      <div class="card" style="padding:24px;background:#fff;border-radius:14px;border:1.5px solid #94a3b8;display:flex;flex-direction:column;justify-content:space-between">
        <div>
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
            <span class="badge silver" style="font-size:12px;padding:4px 10px">{!! svg_icon('award', 15) !!} سطح ۲</span>
            <span style="font-size:12px;color:var(--muted)">پایه ماهانه: ۱,۵۰۰,۰۰۰ تومان</span>
          </div>
          <h3 style="margin:0 0 14px;font-size:18px;color:#334155">ویژه نقره‌ای</h3>
          <p style="font-size:13px;color:var(--muted);line-height:1.6;margin-bottom:16px">
            اولویت بالاتر نسبت به برنزی و شرکت‌های عادی با شانس بازدید و کلیک چندبرابری.
          </p>

          <div style="background:#f8fafc;padding:12px;border-radius:8px;font-size:13px;margin-bottom:16px">
            <div style="display:flex;justify-content:space-between;padding:4px 0"><span>۱ ماهه:</span> <b>۱/۵۰۰/۰۰۰ تومان</b></div>
            <div style="display:flex;justify-content:space-between;padding:4px 0"><span>۳ ماهه (۱۰٪ تخفیف):</span> <b>۴/۰۵۰/۰۰۰ تومان</b></div>
            <div style="display:flex;justify-content:space-between;padding:4px 0"><span>۶ ماهه (۲۰٪ تخفیف):</span> <b>۷/۲۰۰/۰۰۰ تومان</b></div>
            <div style="display:flex;justify-content:space-between;padding:4px 0;color:#16a34a;font-weight:700"><span>۱ ساله (۳۰٪ تخفیف):</span> <b>۱۲/۶۰۰/۰۰۰ تومان</b></div>
          </div>
        </div>

        <a href="#order_section" class="btn ghost" style="width:100%;text-align:center;box-sizing:border-box">سفارش پلن نقره‌ای</a>
      </div>

      <!-- پلن طلایی -->
      <div class="card" style="padding:24px;background:#fffbeb;border-radius:14px;border:2px solid #eab308;display:flex;flex-direction:column;justify-content:space-between;box-shadow:0 4px 15px rgba(234,179,8,0.15)">
        <div>
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
            <span class="badge gold" style="font-size:12px;padding:4px 12px;font-weight:800">{!! svg_icon('award', 15) !!} بالاترین اولویت</span>
            <span style="font-size:12px;color:#854d0e">پایه ماهانه: ۲,۰۰۰,۰۰۰ تومان</span>
          </div>
          <h3 style="margin:0 0 14px;font-size:18px;color:#854d0e">ویژه طلایی</h3>
          <p style="font-size:13px;color:#713f12;line-height:1.6;margin-bottom:16px">
            صدرنشین دایرکتوری و نتایج جستجو، بالاترین اولویت نمایش و نشان طلایی اعتبار.
          </p>

          <div style="background:#fef9c3;padding:12px;border-radius:8px;font-size:13px;margin-bottom:16px;border:1px solid #fef08a">
            <div style="display:flex;justify-content:space-between;padding:4px 0"><span>۱ ماهه:</span> <b>۲/۰۰۰/۰۰۰ تومان</b></div>
            <div style="display:flex;justify-content:space-between;padding:4px 0"><span>۳ ماهه (۱۰٪ تخفیف):</span> <b>۵/۴۰۰/۰۰۰ تومان</b></div>
            <div style="display:flex;justify-content:space-between;padding:4px 0"><span>۶ ماهه (۲۰٪ تخفیف):</span> <b>۹/۶۰۰/۰۰۰ تومان</b></div>
            <div style="display:flex;justify-content:space-between;padding:4px 0;color:#854d0e;font-weight:800"><span>۱ ساله (۳۰٪ تخفیف):</span> <b>۱۶/۸۰۰/۰۰۰ تومان</b></div>
          </div>
        </div>

        <a href="#order_section" class="btn" style="width:100%;text-align:center;background:#eab308;color:#000;font-weight:800;box-sizing:border-box">
          {!! svg_icon('star-filled', 15) !!} سفارش پلن طلایی
        </a>
      </div>

    </div>
  </div>

  <!-- سایر جایگاه‌های تبلیغاتی بنری و سوشال -->
  <div class="grid-2" style="margin-bottom:36px">
    
    <div class="card" style="padding:24px;background:#fff;border-radius:14px;border:1px solid var(--border)">
      <h3 style="margin:0 0 12px;font-size:17px;color:var(--text);display:flex;align-items:center;gap:8px">
        <span>{!! svg_icon('image', 15) !!}</span> جایگاه‌های بنری سایت
      </h3>
      <div style="font-size:13.5px;color:#334155;line-height:1.9">
        <div><b>بنر اسلایدی ابتدای صفحات اصلی:</b> ۳ ماهه ۱۵ م.ت | ۶ ماهه ۲۷ م.ت | ۱ ساله ۴۸ م.ت</div>
        <div><b>بنرهای ۴تایی در صفحات اصلی:</b> ۳ ماهه ۹ م.ت | ۶ ماهه ۱۶.۲ م.ت | ۱ ساله ۲۸.۸ م.ت</div>
        <div><b>بنر در بالای صفحه شرکت‌ها:</b> ۳ ماهه ۱۵ م.ت | ۶ ماهه ۲۷ م.ت | ۱ ساله ۴۸ م.ت</div>
        <div><b>بنر سایدبار و پایین صفحه شرکت‌ها:</b> ۱ ماهه ۱ م.ت | ۳ ماهه ۲.۷ م.ت | ۶ ماهه ۴.۸ م.ت | ۱ ساله ۸.۴ م.ت</div>
      </div>
    </div>

    <div class="card" style="padding:24px;background:#fff;border-radius:14px;border:1px solid var(--border)">
      <h3 style="margin:0 0 12px;font-size:17px;color:var(--text);display:flex;align-items:center;gap:8px">
        <span>{!! svg_icon('smartphone', 15) !!}</span> تبلیغات شبکه‌های اجتماعی ۲ کیلو کاغذ
      </h3>
      <div style="font-size:13.5px;color:#334155;line-height:1.9">
        <p style="margin:0 0 10px;font-size:13px;color:var(--muted)">
          انتشار محتوا در اینستاگرام، کانال تلگرام، واتساپ و بله ۲ کیلو کاغذ با هزاران مخاطب تخصصی صنعت چاپ و کارتن:
        </p>
        <div><b>درج استوری اینستاگرام:</b> ۱/۰۰۰/۰۰۰ تومان</div>
        <div><b>تولید محتوا و درج پست:</b> ۳/۰۰۰/۰۰۰ تومان</div>
        <div><b>پست + استوری:</b> ۴/۰۰۰/۰۰۰ تومان</div>
        <div><b>پست + استوری اینستاگرام + تلگرام + واتساپ + بله:</b> ۵/۰۰۰/۰۰۰ تومان</div>
      </div>
    </div>

  </div>

  <!-- فرم مشاوره و سفارش تبلیغ -->
  <div id="order_section" class="card" style="padding:32px;background:#fff;border-radius:16px;border:1px solid var(--border)">
    <div style="max-width:650px;margin:0 auto;text-align:center">
      <h3 style="margin:0 0 8px;font-size:20px;color:var(--text)">سفارش و مشاوره تبلیغات</h3>
      <p style="margin:0 0 24px;font-size:14px;color:var(--muted)">
        جهت رزرو جایگاه‌های ویژه، بنرهای صفحه اصلی یا تبلیغات سوشال، با کارشناسان ما تماس بگیرید یا پیام بگذارید:
      </p>

      <div style="display:flex;justify-content:center;gap:16px;flex-wrap:wrap;margin-bottom:24px">
        <a href="tel:02128421075" class="btn" style="padding:12px 28px;font-size:15px;font-weight:700">
          {!! svg_icon('phone', 15) !!} تماس با واحد تبلیغات: ۰۲۱۲۸۴۲۱۰۷۵
        </a>
        <a href="https://wa.me/989121234567" target="_blank" class="btn ghost" style="padding:12px 28px;font-size:15px">
          {!! svg_icon('message', 15) !!} پیام در واتساپ / بله
        </a>
      </div>

      <p style="margin:0;font-size:12.5px;color:var(--muted)">
        {!! svg_icon('lightbulb', 15) !!} همچنین اگر شرکتی در سایت ثبت کرده‌اید، می‌توانید از طریق <a href="{{ route('dashboard') }}#subscription" style="color:var(--primary);font-weight:700">میز کار</a> به بخش اشتراک مراجعه نمایید.
      </p>
    </div>
  </div>

</div>
@endsection
