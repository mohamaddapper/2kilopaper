<?php
/**
 * اسکریپت راه‌اندازی موقت دیتابیس در هاست بدون SSH
 * توجه: پس از اجرا حتماً این فایل را از هاست حذف کنید!
 */

// یافتن مسیر لاراول
$autoloadPath = null;
if (file_exists(__DIR__ . '/../laravel-app/vendor/autoload.php')) {
    $baseDir = __DIR__ . '/../laravel-app';
} elseif (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    $baseDir = __DIR__ . '/..';
} else {
    die('<h3 style="color:red;font-family:tahoma;direction:rtl">خطا: پوشه vendor یا پروژه لاراول پیدا نشد! مطمئن شوید vendor.zip در پوشه لاراول اکسترکت شده است.</h3>');
}

require $baseDir . '/vendor/autoload.php';
$app = require_once $baseDir . '/bootstrap/app.php';

use Illuminate\Support\Facades\Artisan;

echo '<!doctype html><html dir="rtl" lang="fa"><head><meta charset="utf-8"><title>راه‌اندازی دیتابیس</title>';
echo '<style>body{font-family:tahoma,sans-serif;padding:30px;background:#f8f9fa;line-height:1.8} pre{background:#222;color:#a6e22e;padding:15px;border-radius:6px;direction:ltr;text-align:left}</style></head><body>';
echo '<h2>عملیات راه‌اندازی دیتابیس لاراول</h2>';

try {
    echo '<p>در حال اجرای Migration و Seeding اولیه...</p>';
    Artisan::call('migrate --seed --force');
    echo '<pre>' . htmlspecialchars(Artisan::output()) . '</pre>';

    Artisan::call('optimize:clear');
    echo '<p style="color:green;font-weight:bold">✓ دیتابیس با موفقیت ساخته شد و اطلاعات اولیه (دسته‌ها و شهرها) ثبت شدند!</p>';
    echo '<div style="background:#fff3cd;color:#856404;padding:15px;border-radius:6px;border:1px solid #ffeeba;margin-top:20px">';
    echo '<strong>هشدار امنیتی بسیار مهم:</strong> لطفاً همین الان این فایل (<code>setup.php</code>) را از پوشه public_html حذف کنید.';
    echo '</div>';
    echo '<p><a href="/" style="display:inline-block;margin-top:15px;padding:10px 20px;background:#0d6efd;color:#fff;text-decoration:none;border-radius:5px">رفتن به صفحه اصلی سایت</a></p>';
} catch (\Throwable $e) {
    echo '<p style="color:red;font-weight:bold">خطا در اتصال به دیتابیس یا اجرای دستورات:</p>';
    echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
    echo '<p>لطفاً نام دیتابیس، نام کاربری و رمز عبور را در فایل .env هاست بررسی کنید.</p>';
}

echo '</body></html>';
