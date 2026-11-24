<?php

// Polyfill for Symfony ErrorRenderer on shared hosts where highlight_file is disabled
namespace Symfony\Component\ErrorHandler\ErrorRenderer {
    if (!function_exists('Symfony\Component\ErrorHandler\ErrorRenderer\highlight_file')) {
        function highlight_file($file, $return = false) {
            $content = (is_string($file) && is_file($file) && is_readable($file)) ? file_get_contents($file) : '';
            $html = '<pre style="background:#1e293b;color:#f8fafc;padding:12px;border-radius:6px;overflow:auto;font-family:monospace;font-size:12px;line-height:1.6"><code>' . htmlspecialchars($content) . '</code></pre>';
            if ($return) {
                return $html;
            }
            echo $html;
            return true;
        }
    }
}

namespace {
define('LARAVEL_START', microtime(true));

if (file_exists(__DIR__ . '/../laravel-app/vendor/autoload.php')) {
    $baseDir = __DIR__ . '/../laravel-app';
} elseif (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    $baseDir = __DIR__ . '/..';
} else {
    die('<h3 style="color:red;font-family:tahoma;direction:rtl">خطا: پوشه vendor یا پروژه لاراول پیدا نشد!</h3>');
}

// Auto-sync public files if public_html is separate from repo base directory
if ($baseDir !== __DIR__) {
    if (file_exists($baseDir . '/public/assets/style.css')) {
        if (!file_exists(__DIR__ . '/assets')) {
            @mkdir(__DIR__ . '/assets', 0755, true);
        }
        @copy($baseDir . '/public/assets/style.css', __DIR__ . '/assets/style.css');
    }
    if (file_exists($baseDir . '/public/index.php')) {
        @copy($baseDir . '/public/index.php', __DIR__ . '/index.php');
    }
    if (file_exists($baseDir . '/public/setup.php') && realpath(__DIR__ . '/setup.php') !== realpath($baseDir . '/public/setup.php')) {
        @copy($baseDir . '/public/setup.php', __DIR__ . '/setup.php');
    }
    // Sync company uploads (logos, catalogs, gallery)
    foreach (['logos', 'catalogs', 'gallery', 'covers'] as $sub) {
        $srcDir = $baseDir . '/public/uploads/' . $sub;
        $dstDir = __DIR__ . '/uploads/' . $sub;
        if (is_dir($srcDir)) {
            if (!file_exists($dstDir)) {
                @mkdir($dstDir, 0755, true);
            }
            $dirFiles = @scandir($srcDir);
            if ($dirFiles) {
                foreach ($dirFiles as $f) {
                    if ($f !== '.' && $f !== '..' && !file_exists($dstDir . '/' . $f)) {
                        @copy($srcDir . '/' . $f, $dstDir . '/' . $f);
                    }
                }
            }
        }
    }
}

require $baseDir . '/vendor/autoload.php';
$app = require_once $baseDir . '/bootstrap/app.php';

$action = $_GET['action'] ?? 'all';

echo '<!doctype html><html dir="rtl" lang="fa"><head><meta charset="utf-8"><title>به‌روزرسانی خودکار سایت</title>';
echo '<style>body{font-family:tahoma,sans-serif;padding:25px;background:#f8f9fa;line-height:1.8;color:#1e293b} pre{background:#1e293b;color:#38bdf8;padding:12px;border-radius:6px;direction:ltr;text-align:left;white-space:pre-wrap;font-size:13px} .btn{display:inline-block;padding:8px 16px;background:#0284c7;color:#fff;text-decoration:none;border-radius:5px;font-weight:bold;font-size:13px;margin-left:6px}</style></head><body>';
echo '<div style="max-width:800px;margin:0 auto;background:#fff;padding:24px;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.05)">';
echo '<h2 style="margin-top:0;color:#0369a1">⚙️ ابزار به‌روزرسانی آنلاین بدون نیاز به ترمینال</h2>';
echo '<div style="margin-bottom:15px;display:flex;gap:8px;flex-wrap:wrap">';
echo '<a href="setup.php?action=all" class="btn">🚀 به‌روزرسانی کامل (گیت + کش + مایگریشن)</a>';
echo '<a href="setup.php?action=import_companies" class="btn" style="background:#059669">🏢 ثبت کامل شرکت‌ها و لوگوها (۹۱۹ شرکت)</a>';
echo '<a href="setup.php?action=clear" class="btn" style="background:#64748b">🧹 فقط پاک‌سازی کش و ویوها</a>';
echo '<a href="setup.php?action=seed" class="btn" style="background:#475569">🌱 ثبت مجدد شهرها و دسته‌ها</a>';
echo '</div><hr style="border:none;border-top:1px solid #e2e8f0;margin:15px 0">';

try {
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    \Illuminate\Support\Facades\Schema::defaultStringLength(191);

    // 1. Try Git Pull via PHP
    if (in_array($action, ['all', 'pull'])) {
        if (function_exists('shell_exec')) {
            echo '<p><b>۱. دریافت آخرین کدها از GitHub (Git Pull):</b></p>';
            $gitOutput = @shell_exec('cd ' . escapeshellarg($baseDir) . ' && git pull origin main 2>&1');
            if ($gitOutput) {
                echo '<pre>' . htmlspecialchars($gitOutput) . '</pre>';
            } else {
                echo '<p style="color:#d97706;font-size:13px">دستور شل در هاست خروجی نداد یا دسترسی به گیت از طریق شل بسته است (در این صورت از بخش Git Version Control سی‌پنل استفاده کنید).</p>';
            }
        }
    }

    // 2. Clear Caches
    echo '<p><b>۲. پاک‌سازی کش‌ها و کامپایل مجدد فایل‌ها:</b></p>';
    $kernel->call('optimize:clear');
    echo '<pre>' . htmlspecialchars($kernel->output()) . '</pre>';

    // 3. Migrations & Schema Adjustments
    if (in_array($action, ['all', 'migrate', 'import_companies'])) {
        echo '<p><b>۳. بررسی و اجرای مایگریشن‌های جدید دیتابیس:</b></p>';
        try {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE companies MODIFY phone VARCHAR(255) NULL");
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE companies MODIFY address TEXT NULL");
        } catch (\Throwable $e) {}
        $kernel->call('migrate', ['--force' => true]);
        echo '<pre>' . htmlspecialchars($kernel->output()) . '</pre>';
    }

    // 4. Seeds & Import Companies
    if ($action === 'import_companies') {
        echo '<p><b>۴. ثبت کامل شهرها، دسته‌ها و شرکت‌ها به همراه لوگوها:</b></p>';
        @set_time_limit(300);
        @ini_set('memory_limit', '512M');
        $kernel->call('db:seed', ['--class' => 'CategorySeeder', '--force' => true]);
        echo '<pre>' . htmlspecialchars($kernel->output()) . '</pre>';
        $kernel->call('db:seed', ['--class' => 'LocationSeeder', '--force' => true]);
        echo '<pre>' . htmlspecialchars($kernel->output()) . '</pre>';
        $kernel->call('db:seed', ['--class' => 'CompanySeeder', '--force' => true]);
        echo '<pre>' . htmlspecialchars($kernel->output()) . '</pre>';
    } elseif ($action === 'seed') {
        echo '<p><b>۴. ورود اطلاعات دسته‌ها و استان/شهرها:</b></p>';
        $kernel->call('db:seed', ['--class' => 'CategorySeeder', '--force' => true]);
        $kernel->call('db:seed', ['--class' => 'LocationSeeder', '--force' => true]);
        echo '<pre>' . htmlspecialchars($kernel->output()) . '</pre>';
    }

    // 5. Make Admin Tool
    if ($action === 'make_admin' || !empty($_POST['admin_phone'])) {
        $phoneInput = trim($_POST['admin_phone'] ?? ($_GET['phone'] ?? ''));
        if (function_exists('normalize_phone')) {
            $phoneInput = normalize_phone($phoneInput);
        }
        if ($phoneInput) {
            $adminU = \App\Models\User::firstOrCreate(['phone' => $phoneInput]);
            $adminU->is_admin = true;
            $adminU->save();
            echo '<div style="background:#f0fdf4;border:1.5px solid #22c55e;color:#15803d;padding:14px;border-radius:8px;margin:15px 0;font-weight:bold;font-size:14px">✓ شماره ' . htmlspecialchars($phoneInput) . ' با موفقیت به مدیر ارشد سیستم (Admin) ارتقا یافت! اکنون می‌توانید تمام شرکت‌ها را ویرایش و مدیریت کنید.</div>';
        }
    }

    echo '<p style="color:#16a34a;font-weight:bold;font-size:18px">✓ عملیات با موفقیت انجام شد!</p>';
    echo '<div style="display:flex;gap:10px;flex-wrap:wrap;margin:15px 0">';
    echo '<a href="/admin/companies" class="btn" style="background:#0284c7;padding:10px 20px;font-size:14px">🏢 مدیریت و ویرایش تمام شرکت‌ها (پنل ادمین)</a>';
    echo '<a href="/dashboard" class="btn" style="background:#16a34a;padding:10px 20px;font-size:14px">ورود به میز کار</a>';
    echo '</div>';

    // Form to grant Admin to any phone number
    echo '<form method="post" action="setup.php?action=make_admin" style="margin-top:24px;background:#f8fafc;padding:16px;border:1px solid #cbd5e1;border-radius:8px">';
    echo '<label style="font-weight:bold;font-size:13.5px;display:block;margin-bottom:6px;color:#0f172a">👑 ارتقای شماره همراه شما به مدیر ارشد سایت (Admin):</label>';
    echo '<p style="font-size:12px;color:#64748b;margin:0 0 10px">اگر با شماره شخصی خود لاگین کرده‌اید و به پنل ادمین یا دکمه‌های ویرایش دسترسی ندارید، شماره خود را وارد کنید و دکمه را بزنید:</p>';
    echo '<div style="display:flex;gap:8px;flex-wrap:wrap">';
    echo '<input type="text" name="admin_phone" placeholder="شماره موبایل شما (مثال: 09121234567)" style="padding:9px 12px;border:1px solid #cbd5e1;border-radius:6px;flex:1;min-width:200px;direction:ltr;font-size:14px">';
    echo '<button type="submit" class="btn" style="background:#0f172a;cursor:pointer;border:none">ثبت دسترسی ادمین کل</button>';
    echo '</div></form>';
} catch (\Throwable $e) {
    echo '<p style="color:#dc2626;font-weight:bold;font-size:16px">خطا در اجرای عملیات:</p>';
    echo '<pre style="color:#f87171;background:#450a0a">' . htmlspecialchars($e->getMessage()) . '</pre>';
}

echo '</div></body></html>';
}
