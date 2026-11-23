<?php
define('LARAVEL_START', microtime(true));

if (file_exists(__DIR__ . '/../laravel-app/vendor/autoload.php')) {
    $baseDir = __DIR__ . '/../laravel-app';
} elseif (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    $baseDir = __DIR__ . '/..';
} else {
    die('<h3 style="color:red;font-family:tahoma;direction:rtl">خطا: پوشه vendor یا پروژه لاراول پیدا نشد!</h3>');
}

require $baseDir . '/vendor/autoload.php';
$app = require_once $baseDir . '/bootstrap/app.php';

echo '<!doctype html><html dir="rtl" lang="fa"><head><meta charset="utf-8"><title>به‌روزرسانی دیتابیس</title>';
echo '<style>body{font-family:tahoma,sans-serif;padding:30px;background:#f8f9fa;line-height:1.8} pre{background:#222;color:#a6e22e;padding:15px;border-radius:6px;direction:ltr;text-align:left;white-space:pre-wrap}</style></head><body>';
echo '<h2>عملیات به‌روزرسانی فیلدهای دیتابیس لاراول</h2>';

try {
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    \Illuminate\Support\Facades\Schema::defaultStringLength(191);

    echo '<p><b>در حال اجرای مایگریشن‌های جدید...</b></p>';
    $kernel->call('migrate', ['--force' => true]);
    echo '<pre>' . htmlspecialchars($kernel->output()) . '</pre>';

    echo '<p><b>در حال ورود اطلاعات ۳۱ استان و ۳۶۸ شهر از فایل CSV...</b></p>';
    $kernel->call('db:seed', ['--class' => 'LocationSeeder', '--force' => true]);
    echo '<pre>' . htmlspecialchars($kernel->output()) . '</pre>';

    $kernel->call('optimize:clear');

    echo '<p style="color:green;font-weight:bold;font-size:20px">✓ دیتابیس با موفقیت به آخرین نسخه فیلدهای جامع به‌روزرسانی شد!</p>';
    echo '<div style="background:#fff3cd;color:#856404;padding:15px;border-radius:6px;border:1px solid #ffeeba;margin-top:20px">';
    echo '<strong>هشدار امنیتی بسیار مهم:</strong> لطفاً همین الان این فایل (<code>setup.php</code>) را از پوشه public_html حذف کنید.';
    echo '</div>';
    echo '<p><a href="/dashboard" style="display:inline-block;margin-top:15px;padding:12px 25px;background:#0d6efd;color:#fff;text-decoration:none;border-radius:5px;font-weight:bold;font-size:16px">ورود به پنل و مشاهده فرم جدید</a></p>';
} catch (\Throwable $e) {
    echo '<p style="color:red;font-weight:bold;font-size:18px">خطا در اجرای مایگریشن:</p>';
    echo '<pre style="color:#ff6b6b">' . htmlspecialchars($e->getMessage()) . '</pre>';
}

echo '</body></html>';
