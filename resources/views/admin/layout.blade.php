<!doctype html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ $title ?? 'مدیریت ارشد پلتفرم' }} | ۲ کیلو کاغذ</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css">
  <link rel="stylesheet" href="{{ asset('assets/style.css') }}?v={{ file_exists(public_path('assets/style.css')) ? filemtime(public_path('assets/style.css')) : '2.0' }}">
  <style>
    :root {
      --admin-bg: #0f172a;
      --admin-primary: #0284c7;
      --admin-accent: #f59e0b;
    }
    body {
      background: #f1f5f9;
      color: #1e293b;
      margin: 0;
      font-family: Vazirmatn, system-ui, sans-serif;
    }
    .admin-header {
      background: var(--admin-bg);
      color: #fff;
      padding: 10px 20px;
      min-height: 64px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 100;
      box-shadow: 0 2px 10px rgba(0,0,0,0.15);
      flex-wrap: wrap;
      gap: 12px;
    }
    .admin-nav {
      display: flex;
      gap: 8px;
      align-items: center;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      max-width: 100%;
      padding-bottom: 2px;
    }
    .admin-nav a {
      color: #94a3b8;
      text-decoration: none;
      font-size: 13.5px;
      font-weight: 600;
      padding: 8px 14px;
      border-radius: 8px;
      transition: all .15s;
      white-space: nowrap;
    }
    .admin-nav a:hover, .admin-nav a.active {
      color: #fff;
      background: rgba(255,255,255,0.08);
    }
    .admin-nav a.active {
      background: var(--admin-primary);
      color: #fff;
    }
    .admin-container {
      max-width: 1280px;
      margin: 24px auto 48px;
      padding: 0 16px;
    }
    .stat-card {
      background: #fff;
      border-radius: 12px;
      padding: 18px 20px;
      border: 1px solid #e2e8f0;
      display: flex;
      align-items: center;
      gap: 14px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .stat-icon {
      width: 48px;
      height: 48px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      flex-shrink: 0;
    }
    .admin-table-card {
      background: #fff;
      border-radius: 12px;
      border: 1px solid #e2e8f0;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      margin-top: 18px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .admin-filter-grid {
      display: grid;
      grid-template-columns: 2fr 1fr 1fr 1fr auto;
      gap: 10px;
      align-items: center;
    }
    @media (max-width: 860px) {
      .admin-filter-grid {
        grid-template-columns: 1fr 1fr;
      }
    }
    @media (max-width: 500px) {
      .admin-filter-grid {
        grid-template-columns: 1fr;
      }
    }
    .admin-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13.5px;
      text-align: right;
    }
    .admin-table th {
      background: #f8fafc;
      padding: 12px 16px;
      font-weight: 700;
      color: #475569;
      border-bottom: 1px solid #e2e8f0;
      white-space: nowrap;
    }
    .admin-table td {
      padding: 12px 16px;
      border-bottom: 1px solid #f1f5f9;
      vertical-align: middle;
    }
    .admin-table tr:hover {
      background: #fbfcfe;
    }
    .btn-action {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: 4px 10px;
      border-radius: 6px;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      border: 1px solid transparent;
      font-family: inherit;
      transition: all .15s;
    }
    .btn-approve { background: #dcfce7; color: #15803d; border-color: #86efac; }
    .btn-approve:hover { background: #bbf7d0; }
    .btn-reject { background: #fee2e2; color: #b91c1c; border-color: #fca5a5; }
    .btn-reject:hover { background: #fecaca; }
    .btn-tier { background: #fef3c7; color: #b45309; border-color: #fde68a; }
    .btn-tier:hover { background: #fde68a; }
    .btn-view { background: #e0f2fe; color: #0369a1; border-color: #bae6fd; }
    .btn-view:hover { background: #bae6fd; }
  </style>
  @stack('head')
</head>
<body>

  <!-- هدر پنل ادمین -->
  <header class="admin-header">
    <div style="display:flex;align-items:center;gap:18px">
      <a href="{{ route('admin.dashboard') }}" style="color:#fff;text-decoration:none;font-weight:800;font-size:17px;display:flex;align-items:center;gap:8px">
        <span style="background:var(--admin-accent);color:#000;padding:2px 8px;border-radius:6px;font-size:12px;font-weight:800">مدیریت</span>
        <span>۲ کیلو کاغذ</span>
      </a>

      <nav class="admin-nav">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
          {!! svg_icon('bar-chart', 15) !!} پیشخوان
        </a>
        <a href="{{ route('admin.companies') }}" class="{{ request()->routeIs('admin.companies') ? 'active' : '' }}">
          {!! svg_icon('building', 15) !!} شرکت‌ها
        </a>
        <a href="{{ route('admin.categories') }}" class="{{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
          {!! svg_icon('tag', 15) !!} دسته‌بندی و سئو
        </a>
        <a href="{{ route('admin.jobs') }}" class="{{ request()->routeIs('admin.jobs') ? 'active' : '' }}">
          {!! svg_icon('briefcase', 15) !!} آگهی‌های شغلی
        </a>
        <a href="{{ route('admin.applications') }}" class="{{ request()->routeIs('admin.applications') ? 'active' : '' }}">
          {!! svg_icon('users', 15) !!} رزومه‌ها و متقاضیان
        </a>
        <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
          {!! svg_icon('user', 15) !!} کاربران
        </a>
      </nav>
    </div>

    <div style="display:flex;align-items:center;gap:14px">
      <a href="{{ route('home') }}" target="_blank" style="color:#94a3b8;font-size:13px;text-decoration:none">
        مشاهده سایت ↗
      </a>
      <a href="{{ route('dashboard') }}" style="color:#38bdf8;font-size:13px;text-decoration:none">
        میز کار من
      </a>
      <form method="post" action="{{ route('logout') }}" style="margin:0">
        @csrf
        <button type="submit" style="background:none;border:none;color:#ef4444;font-size:13px;cursor:pointer;font-family:inherit;padding:0">خروج</button>
      </form>
    </div>
  </header>

  <main class="admin-container">
    @if(session('ok'))
      <div style="background:#dcfce7;border:1px solid #86efac;color:#166534;padding:12px 18px;border-radius:8px;margin-bottom:18px;font-size:14px;font-weight:600">
        {!! svg_icon('check', 15) !!} {{ session('ok') }}
      </div>
    @endif
    @if(session('error'))
      <div style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;padding:12px 18px;border-radius:8px;margin-bottom:18px;font-size:14px;font-weight:600">
        {!! svg_icon('x', 15) !!} {{ session('error') }}
      </div>
    @endif

    @yield('content')
  </main>

  <footer style="text-align:center;padding:24px 0;color:#94a3b8;font-size:12.5px">
    پنل مدیریت سیستم ۲ کیلو کاغذ · طراحی شده برای مدیریت B2B صنایع کاغذ، کارتن و چاپ
  </footer>

  @stack('scripts')
</body>
</html>
