<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConvertPersianDigits
{
    /**
     * Handle an incoming request.
     * Convert Persian and Arabic digits to English digits for consistent database storage.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('POST') || $request->isMethod('PUT') || $request->isMethod('PATCH')) {
            $input = $request->all();
            if (!empty($input) && function_exists('fa_to_en_digits')) {
                $request->replace(fa_to_en_digits($input));
            }
        }

        return $next($request);
    }
}
