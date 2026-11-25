@php
  $isCreateFlow = ($mode ?? 'info') === 'create';
@endphp

  <!-- بنر هدر و معرفی ارزش افزوده -->
  <div style="text-align:center;padding:36px 20px;background:#fff;border:1px solid var(--border);border-radius:16px;margin-bottom:32px;box-shadow:0 2px 10px rgba(0,0,0,0.02)">
    <span class="badge" style="background:#e0f2fe;color:#0369a1;font-size:13px;padding:4px 14px;font-weight:700;margin-bottom:12px;display:inline-block">
      {!! svg_icon('gem', 15) !!} {{ $isCreateFlow ? 'مرحله ۱ از ۲ · انتخاب پلن ثبت شرکت' : 'تعرفه اشتراک‌های سالانه و دوره‌ای' }}
    </span>
    <h1 style="margin:0 0 12px;font-size:26px;color:var(--text);line-height:1.5">
      فقط در فهرست نباشید؛ فرصت بیشتری برای انتخاب‌شدن داشته باشید
    </h1>
    <p style="margin:0 auto;max-width:750px;color:var(--muted);font-size:15px;line-height:1.8">
      مشتری پیش از اینکه با شما تماس بگیرد، به دلیلی برای انتخاب شما نیاز دارد. تصویر محصولات، کاتالوگ و معرفی حرفه‌ای مجموعه‌تان می‌تواند شروع این شناخت باشد.
    </p>
  </div>

  <!-- بخش دلایل و مزایای اشتراک -->
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px;margin-bottom:36px">

    <div class="card" style="padding:20px;background:#fff;border-radius:12px;border:1px solid var(--border)">
      <div style="font-size:28px;margin-bottom:8px">{!! svg_icon('rocket', 15) !!}</div>
      <h3 style="margin:0 0 6px;font-size:15.5px;color:var(--text)">بالاتر دیده شوید</h3>
      <p style="margin:0;font-size:13px;color:var(--muted);line-height:1.7">
        شرکت‌های دارای اشتراک در فهرست شرکت‌ها، بالاتر از مجموعه‌های بدون اشتراک نمایش داده می‌شوند.
      </p>
    </div>

    <div class="card" style="padding:20px;background:#fff;border-radius:12px;border:1px solid var(--border)">
      <div style="font-size:28px;margin-bottom:8px">{!! svg_icon('camera', 15) !!}</div>
      <h3 style="margin:0 0 6px;font-size:15.5px;color:var(--text)">محصولاتتان را نشان دهید</h3>
      <p style="margin:0;font-size:13px;color:var(--muted);line-height:1.7">
        با درج تصاویر محصولات و افزودن کاتالوگ PDF، شناخت شفاف‌تری از توانمندی‌های خود به مشتری بدهید.
      </p>
    </div>

    <div class="card" style="padding:20px;background:#fff;border-radius:12px;border:1px solid var(--border)">
      <div style="font-size:28px;margin-bottom:8px">{!! svg_icon('shield', 15) !!}</div>
      <h3 style="margin:0 0 6px;font-size:15.5px;color:var(--text)">حفظ تمرکز مخاطب</h3>
      <p style="margin:0;font-size:13px;color:var(--muted);line-height:1.7">
        با فعال‌سازی اشتراک، تبلیغات سایر شرکت‌ها از صفحه اختصاصی شما حذف می‌شود تا مخاطب فقط شما را ببیند.
      </p>
    </div>

    <div class="card" style="padding:20px;background:#fff;border-radius:12px;border:1px solid var(--border)">
      <div style="font-size:28px;margin-bottom:8px">{!! svg_icon('megaphone', 15) !!}</div>
      <h3 style="margin:0 0 6px;font-size:15.5px;color:var(--text)">جذب نیرو و استخدام</h3>
      <p style="margin:0;font-size:13px;color:var(--muted);line-height:1.7">
        آگهی‌های استخدام مجموعه‌تان را در فضای تخصصی صنعت کاغذ، چاپ و کارتن منتشر کنید.
      </p>
    </div>

  </div>

  <!-- کارت‌های ۴گانه قیمت‌گذاری و پلن‌ها -->
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:20px;margin-bottom:40px;align-items:stretch">

    <!-- پلن ۱: بدون اشتراک -->
    <div class="card" style="padding:24px;background:#fff;border-radius:14px;border:1px solid var(--border);display:flex;flex-direction:column;justify-content:space-between">
      <div>
        <span style="font-size:13px;color:var(--muted);font-weight:600;display:block">عضویت پایه</span>
        <h2 style="margin:6px 0 12px;font-size:20px;color:var(--text)">بدون اشتراک</h2>
        <div style="font-size:24px;font-weight:800;color:var(--text);margin-bottom:14px">
          رایگان
        </div>
        <p style="font-size:12.5px;color:var(--muted);border-bottom:1px solid var(--border);padding-bottom:14px;margin-bottom:16px">
          مدت زمان: نامحدود · ثبت اطلاعات متنی اولیه شرکت
        </p>

        <ul style="list-style:none;padding:0;margin:0;font-size:13px;line-height:2">
          <li style="color:#94a3b8">{!! svg_icon('x', 15) !!} درج تصویر و گالری محصولات</li>
          <li style="color:#94a3b8">{!! svg_icon('x', 15) !!} انتشار آگهی استخدام</li>
          <li style="color:#94a3b8">{!! svg_icon('x', 15) !!} افزودن کاتالوگ محصولات</li>
          <li style="color:#94a3b8">{!! svg_icon('x', 15) !!} ویدیوی معرفی خدمات و کالا</li>
          <li style="color:#94a3b8">{!! svg_icon('x', 15) !!} اطلاع‌رسانی پیامکی</li>
          <li style="color:#ef4444">{!! svg_icon('alert', 15) !!} نمایش تبلیغ سایرین در صفحه شما</li>
          <li style="color:#64748b">نمایش پایین‌تر از شرکت‌های دارای اشتراک</li>
        </ul>
      </div>

      <div style="margin-top:24px">
        <a href="{{ route('companies.create', ['plan' => 'free']) }}" class="btn ghost" style="width:100%;text-align:center;box-sizing:border-box">
          ثبت رایگان شرکت
        </a>
      </div>
    </div>

    <!-- پلن ۲: ۳ ماهه -->
    <div class="card" style="padding:24px;background:#fff;border-radius:14px;border:1px solid var(--border);display:flex;flex-direction:column;justify-content:space-between">
      <div>
        <span style="font-size:13px;color:var(--muted);font-weight:600;display:block">دوره کوتاه‌مدت</span>
        <h2 style="margin:6px 0 12px;font-size:20px;color:var(--text)">اشتراک ۳ ماهه</h2>
        <div style="font-size:24px;font-weight:800;color:var(--primary);margin-bottom:14px">
          ۳/۰۰۰/۰۰۰ <span style="font-size:13px;font-weight:500;color:var(--muted)">تومان</span>
        </div>
        <p style="font-size:12.5px;color:var(--muted);border-bottom:1px solid var(--border);padding-bottom:14px;margin-bottom:16px">
          مدت زمان: ۹۰ روز · شروع پرقدرت با هزینه مناسب
        </p>

        <ul style="list-style:none;padding:0;margin:0;font-size:13px;line-height:2">
          <li style="color:#16a34a;font-weight:600">{!! svg_icon('check', 15) !!} درج تصویر و گالری محصولات</li>
          <li style="color:#16a34a;font-weight:600">{!! svg_icon('check', 15) !!} انتشار آگهی استخدام در سامانه</li>
          <li style="color:#16a34a;font-weight:600">{!! svg_icon('check', 15) !!} افزودن کاتالوگ محصولات (PDF)</li>
          <li style="color:#94a3b8">{!! svg_icon('x', 15) !!} ویدیوی معرفی کالا و خدمات</li>
          <li style="color:#94a3b8">{!! svg_icon('x', 15) !!} اطلاع‌رسانی پیامکی</li>
          <li style="color:#16a34a">{!! svg_icon('check', 15) !!} حذف تبلیغات سایرین از صفحه شما</li>
          <li style="color:#0284c7;font-weight:600">{!! svg_icon('check', 15) !!} اولویت بالاتر از شرکت‌های عادی</li>
        </ul>
      </div>

      <div style="margin-top:24px">
        <a href="{{ $isCreateFlow ? route('companies.create', ['plan' => '3m']) : route('dashboard') . '#subscription' }}" class="btn" style="width:100%;text-align:center;box-sizing:border-box">
          انتخاب اشتراک ۳ ماهه
        </a>
      </div>
    </div>

    <!-- پلن ۳: ۶ ماهه -->
    <div class="card" style="padding:24px;background:#fff;border-radius:14px;border:1px solid var(--border);display:flex;flex-direction:column;justify-content:space-between">
      <div>
        <span style="font-size:13px;color:var(--muted);font-weight:600;display:block">مدت بیشتر با تخفیف</span>
        <h2 style="margin:6px 0 12px;font-size:20px;color:var(--text)">اشتراک ۶ ماهه</h2>
        <div style="font-size:24px;font-weight:800;color:var(--primary);margin-bottom:14px">
          ۴/۰۰۰/۰۰۰ <span style="font-size:13px;font-weight:500;color:var(--muted)">تومان</span>
        </div>
        <p style="font-size:12.5px;color:var(--muted);border-bottom:1px solid var(--border);padding-bottom:14px;margin-bottom:16px">
          مدت زمان: ۱۸۰ روز · فقط ۱ م.ت بیشتر برای دو برابر مدت
        </p>

        <ul style="list-style:none;padding:0;margin:0;font-size:13px;line-height:2">
          <li style="color:#16a34a;font-weight:600">{!! svg_icon('check', 15) !!} درج تصویر و گالری محصولات</li>
          <li style="color:#16a34a;font-weight:600">{!! svg_icon('check', 15) !!} انتشار آگهی استخدام در سامانه</li>
          <li style="color:#16a34a;font-weight:600">{!! svg_icon('check', 15) !!} افزودن کاتالوگ محصولات (PDF)</li>
          <li style="color:#94a3b8">{!! svg_icon('x', 15) !!} ویدیوی معرفی کالا و خدمات</li>
          <li style="color:#94a3b8">{!! svg_icon('x', 15) !!} اطلاع‌رسانی پیامکی</li>
          <li style="color:#16a34a">{!! svg_icon('check', 15) !!} حذف تبلیغات سایرین از صفحه شما</li>
          <li style="color:#0284c7;font-weight:600">{!! svg_icon('check', 15) !!} اولویت بالاتر از شرکت‌های عادی</li>
        </ul>
      </div>

      <div style="margin-top:24px">
        <a href="{{ $isCreateFlow ? route('companies.create', ['plan' => '6m']) : route('dashboard') . '#subscription' }}" class="btn" style="width:100%;text-align:center;box-sizing:border-box">
          انتخاب اشتراک ۶ ماهه
        </a>
      </div>
    </div>

    <!-- پلن ۴: ۱ ساله -->
    <div class="card" style="padding:24px;background:#fefce8;border-radius:14px;border:2px solid #eab308;display:flex;flex-direction:column;justify-content:space-between;position:relative;box-shadow:0 4px 15px rgba(234,179,8,0.15)">
      <div style="position:absolute;top:-13px;left:50%;transform:translateX(-50%);background:#eab308;color:#000;font-size:11.5px;font-weight:800;padding:3px 14px;border-radius:20px;white-space:nowrap;display:inline-flex;align-items:center;gap:4px">
        {!! svg_icon('star-filled', 14) !!} به‌صرفه‌ترین انتخاب
      </div>

      <div>
        <span style="font-size:13px;color:#854d0e;font-weight:700;display:block">کامل‌ترین پکیج سالانه</span>
        <h2 style="margin:6px 0 12px;font-size:20px;color:#713f12">اشتراک ۱ ساله</h2>
        <div style="font-size:24px;font-weight:800;color:#854d0e;margin-bottom:14px">
          ۵/۰۰۰/۰۰۰ <span style="font-size:13px;font-weight:500;color:#a16207">تومان</span>
        </div>
        <p style="font-size:12.5px;color:#854d0e;border-bottom:1px solid #fef08a;padding-bottom:14px;margin-bottom:16px">
          مدت: ۳۶۵ روز · معادل ماهانه فقط ۴۱۷ هزار تومان!
        </p>

        <ul style="list-style:none;padding:0;margin:0;font-size:13px;line-height:2">
          <li style="color:#16a34a;font-weight:700">{!! svg_icon('check', 15) !!} درج تصویر و گالری محصولات</li>
          <li style="color:#16a34a;font-weight:700">{!! svg_icon('check', 15) !!} انتشار آگهی استخدام در سامانه</li>
          <li style="color:#16a34a;font-weight:700">{!! svg_icon('check', 15) !!} افزودن کاتالوگ محصولات (PDF)</li>
          <li style="color:#854d0e;font-weight:700">{!! svg_icon('check', 15) !!} ویدیوی معرفی کالا و خدمات (آپارات)</li>
          <li style="color:#854d0e;font-weight:700">{!! svg_icon('check', 15) !!} اطلاع‌رسانی پیامکی به مدیر</li>
          <li style="color:#16a34a;font-weight:700">{!! svg_icon('check', 15) !!} حذف تبلیغات سایرین از صفحه شما</li>
          <li style="color:#b45309;font-weight:700">{!! svg_icon('star-filled', 15) !!} بالاترین اولویت نمایش در دایرکتوری</li>
        </ul>
      </div>

      <div style="margin-top:24px">
        <a href="{{ $isCreateFlow ? route('companies.create', ['plan' => '12m']) : route('dashboard') . '#subscription' }}" class="btn" style="width:100%;text-align:center;background:#eab308;color:#000;font-weight:800;box-sizing:border-box">
          {!! svg_icon('star-filled', 15) !!} {{ $isCreateFlow ? 'انتخاب اشتراک ۱ ساله' : 'خرید اشتراک سالانه' }}
        </a>
      </div>
    </div>

  </div>

  <!-- راهنمای انتخاب اشتراک و ارتقای تبلیغاتی -->
  <div class="card" style="padding:28px;background:#fff;border-radius:14px;border:1px solid var(--border);margin-bottom:30px">
    <h3 style="margin:0 0 16px;font-size:18px;color:var(--text)">کدام اشتراک برای شما مناسب‌تر است؟</h3>

    <div class="grid-2" style="font-size:14px;color:#334155;line-height:1.8">
      <div>
        <p style="margin:0 0 12px">
          <b>اشتراک ۳ ماهه؛ شروع با دوره کوتاه‌تر:</b> با ۳ میلیون تومان، ۹۰ روز از امکانات گالری، کاتالوگ، انتشار آگهی استخدام، حذف تبلیغات رقبا و جایگاه بالاتر از شرکت‌های رایگان بهره‌مند شوید.
        </p>
        <p style="margin:0">
          <b>اشتراک ۶ ماهه؛ مدت بیشتر با هزینه ماهانه کمتر:</b> با ۴ میلیون تومان، همین امکانات را برای ۱۸۰ روز در اختیار داشته باشید؛ یعنی فقط با ۱ میلیون تومان بیشتر از اشتراک سه‌ماهه، مدت اشتراکتان را دو برابر کنید.
        </p>
      </div>

      <div>
        <p style="margin:0 0 12px">
          <b>اشتراک ۱ ساله؛ کامل‌ترین و به‌صرفه‌ترین انتخاب:</b> با ۵ میلیون تومان، ۳۶۵ روز حضور مداوم داشته باشید و علاوه بر تمامی امکانات بالا، از درج ویدیوی معرفی کالا و خدمات و اطلاع‌رسانی پیامکی نیز بهره‌مند شوید.
        </p>
        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:12px;font-size:13px;color:#166534">
          {!! svg_icon('lightbulb', 15) !!} <b>نیاز به دیده‌شدن در صدر نتایج و بنر اختصاصی دارید؟</b><br>
          پلن‌های ویژه طلایی، نقره‌ای و برنزی و بنرهای سایت را در <a href="{{ route('advertising') }}" style="color:#15803d;font-weight:700">صفحه تبلیغات ۲ کیلو کاغذ</a> مشاهده کنید.
        </div>
      </div>
    </div>
  </div>
