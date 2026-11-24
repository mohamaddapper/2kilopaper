<!doctype html>
<html lang="fa" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $title ?? 'دایرکتوری صنعت کاغذ، چاپ و بسته‌بندی' }} | {{ config('app.name') }}</title>
<meta name="description" content="{{ $description ?? 'دایرکتوری شرکت‌های کاغذ، چاپ و بسته‌بندی ایران' }}">
<link rel="canonical" href="{{ $canonical ?? url()->current() }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css"></noscript>
<link rel="stylesheet" href="{{ asset('assets/style.css') }}?v={{ file_exists(public_path('assets/style.css')) ? filemtime(public_path('assets/style.css')) : '1.2' }}">
@stack('head')
</head>
<body>
<header class="site"><div class="container nav">
  <a class="logo" href="{{ route('home') }}">{{ config('app.name') }}</a>
  @if(!empty($dashboardNav))
    <div style="display:flex;align-items:center;gap:18px">
      @if(auth()->check() && auth()->user()->isAdmin())
        <a href="{{ route('admin.dashboard') }}" style="color:#d97706;font-size:14px;font-weight:700;text-decoration:none">★ پنل مدیریت</a>
      @endif
      <a href="{{ route('directory') }}" style="color:var(--muted);font-size:14px;text-decoration:none">دایرکتوری</a>
      <a href="{{ route('jobs.index') }}" style="color:var(--muted);font-size:14px;text-decoration:none">فرصت‌های شغلی</a>
      <form method="post" action="{{ route('logout') }}" style="display:inline;margin:0">
        @csrf
        <button type="submit" style="background:none;border:none;color:var(--text);font-size:14.5px;font-family:inherit;cursor:pointer;padding:0;font-weight:500">خروج</button>
      </form>
    </div>
  @else
    <ul>
      @if(auth()->check() && auth()->user()->isAdmin())
        <li><a href="{{ route('admin.dashboard') }}" style="color:#d97706;font-weight:700">★ مدیریت</a></li>
      @endif
      <li><a href="{{ route('directory') }}">دایرکتوری</a></li>
      <li><a href="{{ route('jobs.index') }}">فرصت‌های شغلی</a></li>
      <li><a href="{{ route('subscription.fee') }}">تعرفه‌ها</a></li>
      <li><a href="{{ route('advertising') }}">تبلیغات</a></li>
      @auth
        <li><a href="{{ route('dashboard') }}">میز کار</a></li>
        <li><form method="post" action="{{ route('logout') }}">@csrf<button class="btn ghost small">خروج</button></form></li>
      @else
        <li><a href="{{ route('login') }}">ورود</a></li>
      @endauth
    </ul>
  @endif
</div></header>
@yield('hero')
<main class="container">
  @if(session('ok'))<div class="card" style="border-color:#2e9b5b;margin-bottom:16px">{{ session('ok') }}</div>@endif
  @yield('content')
</main>
<footer style="text-align:center;padding:32px 0 24px;color:var(--muted);font-size:13px;border-top:1px solid var(--border);margin-top:40px">
  <div style="display:flex;justify-content:center;gap:18px;margin-bottom:12px;flex-wrap:wrap">
    <a href="{{ route('home') }}" style="color:var(--muted);text-decoration:none">صفحه اصلی</a>
    <a href="{{ route('directory') }}" style="color:var(--muted);text-decoration:none">دایرکتوری شرکت‌ها</a>
    <a href="{{ route('jobs.index') }}" style="color:var(--muted);text-decoration:none">فرصت‌های شغلی</a>
    <a href="{{ route('subscription.fee') }}" style="color:var(--muted);text-decoration:none">هزینه اشتراک‌ها</a>
    <a href="{{ route('advertising') }}" style="color:var(--muted);text-decoration:none">تعرفه تبلیغات</a>
  </div>
  <div>
    © {{ config('app.name') }} · مرکز صنعت کاغذ، چاپ و بسته‌بندی ایران
  </div>
</footer>
</body>
</html>
