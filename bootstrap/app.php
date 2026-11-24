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
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\ConvertPersianDigits::class,
        ]);
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
}
