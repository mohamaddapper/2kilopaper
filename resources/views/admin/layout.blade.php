<!doctype html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ $title ?? 'مدیریت ارشد پلتفرم' }} | ۲ کیلو کاغذ</title>
  <script>
    (function () {
      try {
        if (localStorage.getItem('theme') === 'dark') {
          document.documentElement.classList.add('dark');
        }
      } catch (e) {}
    })();
  </script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css">
  <link rel="stylesheet" href="{{ asset('assets/style.css') }}?v={{ file_exists(public_path('assets/style.css')) ? filemtime(public_path('assets/style.css')) : '2.0' }}">
  <style>
    :root {
      --admin-bg: #0f172a;
      --admin-sidebar: #0b1120;
      --admin-primary: #0284c7;
      --admin-accent: #f59e0b;
    }
    body {
      background: #f1f5f9;
      color: #1e293b;
      margin: 0;
      font-family: Vazirmatn, system-ui, sans-serif;
    }
    html.dark body {
      background: #0b1120;
      color: #f1f5f9;
    }
    .admin-shell {
      display: flex;
      min-height: 100vh;
    }
    .admin-sidebar {
      width: 256px;
      flex-shrink: 0;
      background: var(--admin-bg);
      color: #e2e8f0;
      display: flex;
      flex-direction: column;
      position: sticky;
      top: 0;
      height: 100vh;
      overflow-y: auto;
      border-left: 1px solid #1e293b;
      z-index: 200;
      transition: transform 0.22s ease;
    }
    .admin-sidebar-brand {
      padding: 18px 18px 14px;
      border-bottom: 1px solid #1e293b;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 8px;
    }
    .admin-sidebar-brand a {
      color: #fff;
      text-decoration: none;
      font-weight: 800;
      font-size: 16px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .admin-sidebar-close {
      display: none;
      background: #1e293b;
      border: 1px solid #334155;
      color: #cbd5e1;
      width: 32px;
      height: 32px;
      border-radius: 8px;
      cursor: pointer;
      align-items: center;
      justify-content: center;
      font-size: 18px;
    }
    .admin-nav {
      display: flex;
      flex-direction: column;
      gap: 4px;
      padding: 14px 12px;
      flex: 1;
    }
    .admin-nav-section {
      font-size: 11px;
      font-weight: 700;
      color: #64748b;
      padding: 8px 10px 4px;
    }
    .admin-nav a {
      color: #94a3b8;
      text-decoration: none;
      font-size: 13.5px;
      font-weight: 600;
      padding: 10px 12px;
      border-radius: 9px;
      transition: all .15s;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 8px;
    }
    .admin-nav a .nav-label {
      display: inline-flex;
      align-items: center;
      gap: 9px;
    }
    .admin-nav a:hover {
      color: #f8fafc;
      background: rgba(255,255,255,0.07);
    }
    .admin-nav a.active {
      background: var(--admin-primary);
      color: #ffffff;
      box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
    }
    .admin-nav-badge {
      background: #f59e0b;
      color: #000;
      font-size: 11px;
      font-weight: 800;
      padding: 1px 7px;
      border-radius: 99px;
    }
    .admin-sidebar-footer {
      padding: 12px;
      border-top: 1px solid #1e293b;
      display: flex;
      flex-direction: column;
      gap: 4px;
    }
    .admin-sidebar-footer a,
    .admin-sidebar-footer button {
      display: flex;
      align-items: center;
      gap: 8px;
      width: 100%;
      padding: 8px 12px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      background: transparent;
      border: none;
      cursor: pointer;
      font-family: inherit;
      text-align: right;
      box-sizing: border-box;
    }
    .admin-sidebar-footer a:hover,
    .admin-sidebar-footer button:hover {
      background: rgba(255,255,255,0.07);
    }
    .admin-main-wrap {
      flex: 1;
      min-width: 0;
      display: flex;
      flex-direction: column;
    }
    .admin-topbar {
      background: #ffffff;
      border-bottom: 1px solid #e2e8f0;
      padding: 10px 22px;
      min-height: 58px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      position: sticky;
      top: 0;
      z-index: 100;
    }
    html.dark .admin-topbar {
      background: #111827;
      border-bottom-color: #1e293b;
    }
    .admin-mobile-toggle {
      display: none;
      align-items: center;
      justify-content: center;
      width: 38px;
      height: 38px;
      border-radius: 9px;
      border: 1px solid #cbd5e1;
      background: #f8fafc;
      color: #0f172a;
      cursor: pointer;
      font-size: 18px;
    }
    html.dark .admin-mobile-toggle {
      background: #1e293b;
      border-color: #334155;
      color: #f8fafc;
    }
    .admin-backdrop {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.5);
      z-index: 190;
    }
    .admin-backdrop.open {
      display: block;
    }
    .admin-container {
      width: 100%;
      max-width: 1280px;
      margin: 24px auto 48px;
      padding: 0 22px;
      box-sizing: border-box;
      flex: 1;
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
    html.dark .stat-card,
    html.dark .admin-table-card {
      background: #111827;
      border-color: #1e293b;
      color: #f1f5f9;
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
    @media (max-width: 991px) {
      .admin-sidebar {
        position: fixed;
        right: 0;
        top: 0;
        bottom: 0;
        transform: translateX(100%);
      }
      .admin-sidebar.open {
        transform: translateX(0);
      }
      .admin-sidebar-close,
      .admin-mobile-toggle {
        display: inline-flex;
      }
      .admin-container {
        padding: 0 14px;
      }
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
    html.dark .admin-table th {
      background: #1e293b;
      color: #e2e8f0;
      border-bottom-color: #334155;
    }
    .admin-table td {
      padding: 12px 16px;
      border-bottom: 1px solid #f1f5f9;
      vertical-align: middle;
    }
    html.dark .admin-table td {
      border-bottom-color: #1e293b;
    }
    .admin-table tr:hover {
      background: #fbfcfe;
    }
    html.dark .admin-table tr:hover {
      background: #1e293b;
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

    /* Shared Gear Action Dropdown Menu */
    .gear-dropdown { position: relative; display: inline-block; }
    .gear-btn {
      display: inline-flex; align-items: center; justify-content: center;
      width: 34px; height: 34px; border-radius: 8px;
      border: 1px solid #cbd5e1; background: #f8fafc; color: #475569;
      cursor: pointer; transition: all 0.15s ease;
    }
    .gear-btn:hover { border-color: #0284c7; color: #0284c7; background: #eff6ff; }
    .gear-dropdown-menu {
      display: none; position: fixed; z-index: 9999; min-width: 170px;
      background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px;
      box-shadow: 0 10px 28px rgba(15, 23, 42, 0.14); padding: 6px; text-align: right;
    }
    .gear-dropdown-menu.open { display: block; }
    .gear-dropdown-item {
      display: flex; align-items: center; gap: 8px; width: 100%; padding: 8px 10px;
      border: none; background: transparent; color: #334155; font-size: 13px;
      font-weight: 600; font-family: inherit; text-decoration: none;
      border-radius: 7px; cursor: pointer; text-align: right; box-sizing: border-box;
    }
    .gear-dropdown-item:hover { background: #f1f5f9; color: #0f172a; }
    .gear-dropdown-item.danger { color: #dc2626; }
    .gear-dropdown-item.danger:hover { background: #fef2f2; color: #b91c1c; }
    html.dark .gear-btn { background: #1e293b; border-color: #334155; color: #cbd5e1; }
    html.dark .gear-btn:hover { border-color: #38bdf8; color: #38bdf8; background: #0c233a; }
    html.dark .gear-dropdown-menu { background: #111827; border-color: #334155; box-shadow: 0 12px 30px rgba(0,0,0,0.55); }
    html.dark .gear-dropdown-item { color: #e2e8f0; }
    html.dark .gear-dropdown-item:hover { background: #1e293b; color: #f8fafc; }
    html.dark .gear-dropdown-item.danger { color: #f87171; }
    html.dark .gear-dropdown-item.danger:hover { background: #2b1418; color: #fca5a5; }
  </style>
  @stack('head')
</head>
<body>

  <div class="admin-backdrop" id="adminBackdrop" onclick="toggleAdminSidebar(false)"></div>

  <div class="admin-shell">
    <!-- منوی عمودی سمت راست پنل مدیریت -->
    <aside class="admin-sidebar" id="adminSidebar">
      <div class="admin-sidebar-brand">
        <a href="{{ route('admin.dashboard') }}">
          <span style="background:var(--admin-accent);color:#000;padding:2px 8px;border-radius:6px;font-size:12px;font-weight:800">مدیریت</span>
          <span>۲ کیلو کاغذ</span>
        </a>
        <button type="button" class="admin-sidebar-close" onclick="toggleAdminSidebar(false)" aria-label="بستن منو">×</button>
      </div>

      @php
        $pendingCompaniesCount = 0;
        try {
          $pendingCompaniesCount = \App\Models\Company::where('status', 'pending')->count();
        } catch (\Throwable $e) {}
      @endphp

      @php $u = auth()->user(); @endphp
      <nav class="admin-nav">
        <div class="admin-nav-section">گزارش و تحلیل</div>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
          <span class="nav-label">{!! svg_icon('bar-chart', 16) !!} پیشخوان</span>
        </a>
        @if($u->hasPermission('analytics'))
          <a href="{{ route('admin.analytics') }}" class="{{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
            <span class="nav-label">{!! svg_icon('eye', 16) !!} آمار و بازدیدها</span>
          </a>
        @endif

        <div class="admin-nav-section">مدیریت محتوا</div>
        @if($u->hasPermission('companies'))
          <a href="{{ route('admin.companies') }}" class="{{ request()->routeIs('admin.companies') ? 'active' : '' }}">
            <span class="nav-label">{!! svg_icon('building', 16) !!} شرکت‌ها</span>
            @if($pendingCompaniesCount > 0)
              <span class="admin-nav-badge">{{ $pendingCompaniesCount }}</span>
            @endif
          </a>
        @endif
        @if($u->hasPermission('categories'))
          <a href="{{ route('admin.categories') }}" class="{{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
            <span class="nav-label">{!! svg_icon('tag', 16) !!} دسته‌بندی و سئو</span>
          </a>
        @endif
        @if($u->hasPermission('jobs'))
          <a href="{{ route('admin.jobs') }}" class="{{ request()->routeIs('admin.jobs') ? 'active' : '' }}">
            <span class="nav-label">{!! svg_icon('briefcase', 16) !!} آگهی‌های شغلی</span>
          </a>
        @endif
        @if($u->hasPermission('applications'))
          <a href="{{ route('admin.applications') }}" class="{{ request()->routeIs('admin.applications') ? 'active' : '' }}">
            <span class="nav-label">{!! svg_icon('users', 16) !!} رزومه‌ها و متقاضیان</span>
          </a>
        @endif

        @if($u->hasPermission('users') || $u->hasPermission('logs'))
          <div class="admin-nav-section">سیستم و همکاران</div>
          @if($u->hasPermission('users'))
            <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
              <span class="nav-label">{!! svg_icon('user', 16) !!} کاربران و همکاران</span>
            </a>
          @endif
          @if($u->hasPermission('logs'))
            <a href="{{ route('admin.logs') }}" class="{{ request()->routeIs('admin.logs') ? 'active' : '' }}">
              <span class="nav-label">{!! svg_icon('clock', 16) !!} گزارش فعالیت همکاران</span>
            </a>
          @endif
        @endif
      </nav>

      <div class="admin-sidebar-footer">
        <a href="{{ route('home') }}" target="_blank" style="color:#94a3b8">
          {!! svg_icon('globe', 15) !!} مشاهده سایت ↗
        </a>
        <a href="{{ route('dashboard') }}" style="color:#38bdf8">
          {!! svg_icon('layout', 15) !!} میز کار من
        </a>
        <form method="post" action="{{ route('logout') }}" style="margin:0">
          @csrf
          <button type="submit" style="color:#f87171">
            {!! svg_icon('log-out', 15) !!} خروج از حساب
          </button>
        </form>
      </div>
    </aside>

    <!-- بخش اصلی صفحه -->
    <div class="admin-main-wrap">
      <header class="admin-topbar">
        <div style="display:flex;align-items:center;gap:12px">
          <button type="button" class="admin-mobile-toggle" onclick="toggleAdminSidebar(true)" aria-label="باز کردن منو">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
          </button>
          <span style="font-weight:800;font-size:15px">{{ $title ?? 'پیشخوان مدیریت' }}</span>
        </div>

        <div style="display:flex;align-items:center;gap:12px;font-size:13px">
          <a href="{{ route('home') }}" target="_blank" style="color:#64748b;text-decoration:none;font-weight:600">مشاهده سایت ↗</a>
          <a href="{{ route('dashboard') }}" class="btn ghost small" style="padding:4px 12px;font-size:12.5px">میز کار من</a>
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

      <footer style="text-align:center;padding:20px 0;color:#94a3b8;font-size:12.5px">
        پنل مدیریت سیستم ۲ کیلو کاغذ · طراحی شده برای مدیریت B2B صنایع کاغذ، کارتن و چاپ
      </footer>
    </div>
  </div>

  <script>
    function toggleAdminSidebar(open) {
      const sb = document.getElementById('adminSidebar');
      const bd = document.getElementById('adminBackdrop');
      if (!sb || !bd) return;
      sb.classList.toggle('open', !!open);
      bd.classList.toggle('open', !!open);
    }
    function closeAllGearMenus() {
      document.querySelectorAll('.gear-dropdown-menu.open').forEach(m => m.classList.remove('open'));
    }
    function toggleGearMenu(btn, e) {
      if (e) e.stopPropagation();
      const menu = btn.nextElementSibling;
      const wasOpen = menu.classList.contains('open');
      closeAllGearMenus();
      if (!wasOpen) {
        menu.classList.add('open');
        const rect = btn.getBoundingClientRect();
        const menuRect = menu.getBoundingClientRect();
        let top = rect.bottom + 4;
        if (top + menuRect.height > window.innerHeight - 10) {
          top = Math.max(10, rect.top - menuRect.height - 4);
        }
        let left = rect.left;
        if (left + menuRect.width > window.innerWidth - 10) {
          left = window.innerWidth - menuRect.width - 10;
        }
        menu.style.top = top + 'px';
        menu.style.left = Math.max(10, left) + 'px';
      }
    }
    document.addEventListener('click', closeAllGearMenus);
  </script>
  @stack('scripts')
</body>
</html>
