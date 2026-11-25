<?php

namespace App\Http\Middleware;

use App\Services\ViewTracker;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackSiteViews
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (
            $request->isMethod('GET') &&
            !$request->ajax() &&
            !$request->expectsJson() &&
            $response->getStatusCode() >= 200 &&
            $response->getStatusCode() < 300
        ) {
            $path = ltrim($request->path(), '/');
            $skipPrefixes = ['admin', 'api', 'up', 'setup.php', 'assets', 'storage', 'build', 'track'];
            foreach ($skipPrefixes as $prefix) {
                if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
                    return $response;
                }
            }

            if (preg_match('/\.(css|js|png|jpe?g|gif|svg|ico|webp|woff2?|ttf|map)$/i', $path)) {
                return $response;
            }

            ViewTracker::trackSubject('site', 0);
        }

        return $response;
    }
}
