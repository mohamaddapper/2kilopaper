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
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Auto-detect base directory for cPanel (public_html vs laravel-app)
if (file_exists(__DIR__ . '/../laravel-app/vendor/autoload.php')) {
    $baseDir = __DIR__ . '/../laravel-app';
} elseif (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    $baseDir = __DIR__ . '/..';
} elseif (file_exists(__DIR__ . '/vendor/autoload.php')) {
    $baseDir = __DIR__;
} else {
    die('<h3 style="color:red;font-family:tahoma;direction:rtl">خطا: پوشه vendor یا پروژه لاراول پیدا نشد!</h3>');
}

if (file_exists($maintenance = $baseDir . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

require $baseDir . '/vendor/autoload.php';

(require_once $baseDir . '/bootstrap/app.php')
    ->handleRequest(Request::capture());
}
