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
    public function handle(Request $request, Closure $next, ?string $permission = null): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'ابتدا وارد حساب کاربری خود شوید.');
        }

        $user = auth()->user();
        if (!$user->isAdmin()) {
            abort(403, 'دسترسی غیرمجاز: این بخش فقط مخصوص تیم مدیریت سیستم است.');
        }

        if ($permission !== null && !$user->hasPermission($permission)) {
            abort(403, 'شما مجوز دسترسی به این بخش را ندارید.');
        }

        return $next($request);
    }
}
