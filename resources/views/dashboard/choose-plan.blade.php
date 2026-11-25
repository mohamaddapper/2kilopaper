@extends('layouts.app', ['title' => 'انتخاب پلن ثبت شرکت'])

@section('content')
<div class="page-wrapper" style="margin:28px 0 56px">
  <div style="text-align:center;margin-bottom:28px">
    <span class="badge ok" style="font-size:12px;padding:4px 12px;margin-bottom:10px;display:inline-block">مرحله ۱ از ۲ · انتخاب نوع ثبت شرکت</span>
    <h1 style="margin:0 0 8px;font-size:24px;color:var(--text)">ثبت شرکت و کارخانه در ۲ کیلو کاغذ</h1>
    <p style="margin:0 auto;max-width:660px;font-size:14px;color:var(--muted);line-height:1.8">
      می‌توانید شرکت خود را به صورت <strong>رایگان</strong> ثبت کنید یا با انتخاب یکی از <strong>پلن‌های اشتراک</strong>، از همان ابتدا کاتالوگ PDF، گالری تصاویر، ویدیوی معرفی، شبکه‌های اجتماعی و رتبه بالاتر در نتایج جستجو را فعال کنید.
    </p>
  </div>

  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(235px,1fr));gap:16px;margin-bottom:28px">
    <!-- ثبت رایگان -->
    <div class="card" style="padding:22px;border:1px solid var(--border);border-radius:14px;display:flex;flex-direction:column;justify-content:space-between">
      <div>
        <span style="font-size:12px;color:var(--muted);font-weight:700">شروع سریع</span>
        <h2 style="margin:4px 0 10px;font-size:18px;color:var(--text)">ثبت رایگان</h2>
        <div style="font-size:20px;font-weight:900;color:var(--text);margin-bottom:14px">رایگان</div>
        <ul style="list-style:none;padding:0;margin:0;font-size:13px;line-height:2.1;color:var(--muted)">
          <li>{!! svg_icon('check', 14) !!} درج نام، توضیحات و لوگوی شرکت</li>
          <li>{!! svg_icon('check', 14) !!} ثبت دسته‌بندی فعالیت و آدرس</li>
          <li>{!! svg_icon('check', 14) !!} نمایش شماره تماس اصلی</li>
          <li style="opacity:0.6">{!! svg_icon('x', 14) !!} بدون گالری، کاتالوگ PDF و ویدیو</li>
          <li style="opacity:0.6">{!! svg_icon('x', 14) !!} بدون لینک وب‌سایت و شبکه‌های اجتماعی</li>
        </ul>
      </div>
      <div style="margin-top:18px">
        <a href="{{ route('companies.create', ['plan' => 'free']) }}" class="btn ghost" style="width:100%;text-align:center;box-sizing:border-box">ادامه با ثبت رایگان</a>
      </div>
    </div>

    <!-- اشتراک ۳ ماهه -->
    <div class="card" style="padding:22px;border:1px solid var(--border);border-radius:14px;display:flex;flex-direction:column;justify-content:space-between">
      <div>
        <span style="font-size:12px;color:#0284c7;font-weight:700">دوره ۳ ماهه</span>
        <h2 style="margin:4px 0 10px;font-size:18px;color:var(--text)">اشتراک ۳ ماهه</h2>
        <div style="font-size:20px;font-weight:900;color:var(--primary);margin-bottom:14px">۳/۰۰۰/۰۰۰ تومان</div>
        <ul style="list-style:none;padding:0;margin:0;font-size:13px;line-height:2.1">
          <li>{!! svg_icon('check', 14) !!} باز شدن کاتالوگ PDF و گالری ۵ عکس</li>
          <li>{!! svg_icon('check', 14) !!} ثبت وب‌سایت، واتساپ و شبکه‌های اجتماعی</li>
          <li>{!! svg_icon('check', 14) !!} حذف تبلیغات رقبا از صفحه شما</li>
          <li>{!! svg_icon('check', 14) !!} جایگاه بالاتر از شرکت‌های رایگان</li>
        </ul>
      </div>
      <div style="margin-top:18px">
        <a href="{{ route('companies.create', ['plan' => '3m']) }}" class="btn" style="width:100%;text-align:center;box-sizing:border-box">انتخاب ۳ ماهه و ثبت شرکت</a>
      </div>
    </div>

    <!-- اشتراک ۶ ماهه -->
    <div class="card" style="padding:22px;border:1.5px solid #0284c7;border-radius:14px;display:flex;flex-direction:column;justify-content:space-between">
      <div>
        <span style="font-size:12px;color:#0284c7;font-weight:700">دوره ۶ ماهه</span>
        <h2 style="margin:4px 0 10px;font-size:18px;color:var(--text)">اشتراک ۶ ماهه</h2>
        <div style="font-size:20px;font-weight:900;color:var(--primary);margin-bottom:14px">۴/۰۰۰/۰۰۰ تومان</div>
        <ul style="list-style:none;padding:0;margin:0;font-size:13px;line-height:2.1">
          <li>{!! svg_icon('check', 14) !!} تمام امکانات گالری، کاتالوگ و شبکه‌ها</li>
          <li>{!! svg_icon('check', 14) !!} درج آگهی استخدام نامحدود</li>
          <li>{!! svg_icon('check', 14) !!} حذف کامل تبلیغات رقبا</li>
          <li>{!! svg_icon('check', 14) !!} جایگاه بالاتر از اشتراک ۳ ماهه</li>
        </ul>
      </div>
      <div style="margin-top:18px">
        <a href="{{ route('companies.create', ['plan' => '6m']) }}" class="btn" style="width:100%;text-align:center;box-sizing:border-box">انتخاب ۶ ماهه و ثبت شرکت</a>
      </div>
    </div>

    <!-- اشتراک ۱ ساله -->
    <div class="card" style="padding:22px;border:2px solid #eab308;background:#fefce8;border-radius:14px;display:flex;flex-direction:column;justify-content:space-between">
      <div>
        <span style="font-size:12px;color:#854d0e;font-weight:800">{!! svg_icon('star-filled', 14) !!} به‌صرفه‌ترین انتخاب</span>
        <h2 style="margin:4px 0 10px;font-size:18px;color:#713f12">اشتراک ۱ ساله</h2>
        <div style="font-size:20px;font-weight:900;color:#854d0e;margin-bottom:14px">۵/۰۰۰/۰۰۰ تومان</div>
        <ul style="list-style:none;padding:0;margin:0;font-size:13px;line-height:2.1;color:#713f12">
          <li>{!! svg_icon('check', 14) !!} تمام امکانات گالری، کاتالوگ، ویدیو و استخدام</li>
          <li>{!! svg_icon('check', 14) !!} ثبت کامل وب‌سایت و شبکه‌های اجتماعی</li>
          <li>{!! svg_icon('check', 14) !!} حذف کامل تبلیغات رقبا</li>
          <li>{!! svg_icon('star-filled', 14) !!} بالاترین اولویت در بین اشتراک‌ها</li>
        </ul>
      </div>
      <div style="margin-top:18px">
        <a href="{{ route('companies.create', ['plan' => '12m']) }}" class="btn" style="width:100%;text-align:center;background:#eab308;color:#000;font-weight:800;box-sizing:border-box">انتخاب ۱ ساله و ثبت شرکت</a>
      </div>
    </div>
  </div>

  <!-- توضیح ویژه کردن تبلیغاتی -->
  <div class="card" style="padding:18px 22px;border-radius:12px;border:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">
    <div>
      <b style="font-size:14.5px;color:var(--text)">{!! svg_icon('crown', 15) !!} می‌خواهید در صدر صفحه اول، دسته‌بندی‌ها و جستجو باشید؟</b>
      <p style="margin:4px 0 0;font-size:13px;color:var(--muted)">
        پس از ثبت شرکت، می‌توانید از بخش <strong>«ویژه کردن تبلیغاتی» (برنزی، نقره‌ای یا طلایی)</strong> در میز کار خود استفاده کنید تا بالاتر از همه شرکت‌ها دیده شوید.
      </p>
    </div>
    <a href="{{ route('advertising') }}" target="_blank" class="btn ghost small">مشاهده تعرفه ویژه کردن ↗</a>
  </div>
</div>
@endsection
