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
<link rel="stylesheet" href="{{ asset('assets/style.css') }}">
@stack('head')
</head>
<body>
<header class="site"><div class="container nav">
  <a class="logo" href="{{ route('home') }}">{{ config('app.name') }}</a>
  <ul>
    <li><a href="{{ route('directory') }}">دایرکتوری</a></li>
    @auth
      <li><a href="{{ route('dashboard') }}">پنل من</a></li>
      <li><form method="post" action="{{ route('logout') }}">@csrf<button class="btn ghost small">خروج</button></form></li>
    @else
      <li><a href="{{ route('login') }}">ورود</a></li>
    @endauth
  </ul>
</div></header>
@yield('hero')
<main class="container">
  @if(session('ok'))<div class="card" style="border-color:#2e9b5b;margin-bottom:16px">{{ session('ok') }}</div>@endif
  @yield('content')
</main>
<footer>© {{ config('app.name') }}</footer>
</body>
</html>
