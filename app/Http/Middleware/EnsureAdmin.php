<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'ابتدا وارد حساب کاربری خود شوید.');
        }

        if (!auth()->user()->isAdmin()) {
            abort(403, 'دسترسی غیرمجاز: این بخش فقط مخصوص مدیران ارشد سیستم است.');
        }

        return $next($request);
    }
}
