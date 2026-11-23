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

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../vendor/autoload.php';

(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
}
