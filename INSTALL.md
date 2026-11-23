# نصب (روی کامپیوتر خودتان، نیاز به PHP 8.2+ و Composer)

1. composer create-project laravel/laravel laravel-app "^12.0"
2. محتوای پوشه‌ی overlay را روی laravel-app کپی کنید (با جایگزینی فایل‌ها). `public/` را فعلاً کپی نکنید مگر مرحله ۶.
3. در composer.json داخل "autoload" این را اضافه کنید:
     "files": ["app/helpers.php"]
   سپس: composer dump-autoload
4. محتوای .env.additions را به .env اضافه کنید (مقادیر تکراری را حذف کنید)، APP_KEY با `php artisan key:generate`.
   اگر config('gap') ... پس از تغییر .env: php artisan config:clear
5. روی لوکال تست (با SMS_DRIVER=log، کد در storage/logs/laravel.log): 
     php artisan migrate --seed && php artisan serve
6. آپلود روی هاست:
   - `composer install --no-dev --optimize-autoloader` سپس کل laravel-app (به‌جز public) را zip و در /home/USER/laravel-app آپلود کنید.
   - محتوای laravel-app/public (به‌علاوه‌ی public/index.php و public/assets از overlay) را داخل public_html بگذارید.
   - در app/Providers/AppServiceProvider.php داخل register():
        $this->app->usePublicPath(base_path('../public_html'));
   - در cPanel یک دیتابیس MySQL بسازید و در .env بنویسید؛ در Terminal/پشتیبانی یا با اسکریپت موقت migrate را اجرا کنید
     (چون SSH ندارید: فایل موقت public_html/m.php که Artisan::call('migrate --seed --force') را صدا بزند؛ بعد از اجرا حتماً حذفش کنید).
   - Cron (هر دقیقه): /usr/local/bin/php /home/USER/laravel-app/artisan schedule:run
7. فعال‌سازی Redis در هاست اصلی: در .env سه مقدار SESSION_DRIVER, CACHE_STORE, QUEUE_CONNECTION را redis کنید،
   REDIS_* را پر کنید، `composer require predis/predis` (یا افزونه phpredis) و config:clear.
