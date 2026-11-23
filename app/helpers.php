<?php
if (!function_exists('slugify')) {
    function slugify(string $s): string
    {
        $s = trim(preg_replace('/[^\p{L}\p{N}]+/u', '-', $s), '-');
        return mb_strtolower($s) ?: 'x';
    }
}
if (!function_exists('fa_to_en_digits')) {
    function fa_to_en_digits($val)
    {
        if (is_array($val)) {
            return array_map('fa_to_en_digits', $val);
        }
        if (!is_string($val)) {
            return $val;
        }
        return strtr($val, [
            '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
            '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
            '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
            '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
        ]);
    }
}

if (!function_exists('normalize_phone')) {
    function normalize_phone(?string $p): string
    {
        if (!$p) return '';
        $p = fa_to_en_digits($p);
        return preg_replace('/[^\d+]+/', '', $p);
    }
}

if (!function_exists('jalali_date')) {
    function jalali_date($date): string
    {
        if (!$date) return '';
        $ts = is_numeric($date) ? (int)$date : strtotime((string)$date);
        if (!$ts) return '';

        if (class_exists('\IntlDateFormatter')) {
            try {
                $formatter = new \IntlDateFormatter(
                    'fa_IR@calendar=persian',
                    \IntlDateFormatter::FULL,
                    \IntlDateFormatter::NONE,
                    'Asia/Tehran',
                    \IntlDateFormatter::TRADITIONAL,
                    'd MMMM، yyyy'
                );
                $res = $formatter->format($ts);
                if ($res) return $res;
            } catch (\Throwable $e) {}
        }

        $gy = (int)date('Y', $ts);
        $gm = (int)date('m', $ts);
        $gd = (int)date('d', $ts);

        $g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
        $gy2 = ($gm > 2) ? ($gy + 1) : $gy;
        $days = 355666 + (365 * $gy) + (int)(($gy2 + 3) / 4) - (int)(($gy2 + 99) / 100) + (int)(($gy2 + 399) / 400) + $gd + $g_d_m[$gm - 1];
        $jy = -1595 + (33 * (int)($days / 12053));
        $days %= 12053;
        $jy += 4 * (int)($days / 1461);
        $days %= 1461;
        if ($days > 365) {
            $jy += (int)(($days - 1) / 365);
            $days = ($days - 1) % 365;
        }
        if ($days < 186) {
            $jm = 1 + (int)($days / 31);
            $jd = 1 + ($days % 31);
        } else {
            $jm = 7 + (int)(($days - 186) / 30);
            $jd = 1 + (($days - 186) % 30);
        }
        $months = ['', 'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'];
        return $jd . ' ' . ($months[$jm] ?? '') . '، ' . $jy;
    }
}
