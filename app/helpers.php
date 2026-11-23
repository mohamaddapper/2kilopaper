<?php
if (!function_exists('slugify')) {
    function slugify(string $s): string
    {
        $s = trim(preg_replace('/[^\p{L}\p{N}]+/u', '-', $s), '-');
        return mb_strtolower($s) ?: 'x';
    }
}
if (!function_exists('normalize_phone')) {
    function normalize_phone(string $p): string
    {
        $p = strtr($p, ['۰'=>'0','۱'=>'1','۲'=>'2','۳'=>'3','۴'=>'4','۵'=>'5','۶'=>'6','۷'=>'7','۸'=>'8','۹'=>'9',
                        '٠'=>'0','١'=>'1','٢'=>'2','٣'=>'3','٤'=>'4','٥'=>'5','٦'=>'6','٧'=>'7','٨'=>'8','٩'=>'9']);
        return preg_replace('/\D+/', '', $p);
    }
}
