<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ViewTracker
{
    public static function isBot(?string $userAgent = null): bool
    {
        $ua = strtolower($userAgent ?? (request()->userAgent() ?? ''));
        if ($ua === '') {
            return true;
        }
        $bots = [
            'bot', 'crawl', 'spider', 'slurp', 'mediapartners', 'uptime',
            'feed', 'curl', 'wget', 'lighthouse', 'headless', 'python', 'postman',
            'ahrefs', 'semrush', 'mj12bot', 'dotbot', 'bingpreview', 'facebookexternalhit',
            'telegrambot', 'whatsapp', 'yandex', 'baiduspider',
        ];
        foreach ($bots as $bot) {
            if (str_contains($ua, $bot)) {
                return true;
            }
        }
        return false;
    }

    public static function detectSource(): string
    {
        $referer = strtolower((string) request()->headers->get('referer', ''));
        if ($referer === '') {
            return 'source_direct';
        }

        if (
            str_contains($referer, 'google.') ||
            str_contains($referer, 'bing.') ||
            str_contains($referer, 'yahoo.') ||
            str_contains($referer, 'duckduckgo.') ||
            str_contains($referer, 'yandex.')
        ) {
            return 'source_google';
        }

        $host = strtolower((string) request()->getHost());
        if ($host !== '' && str_contains($referer, $host)) {
            if (str_contains($referer, '/directory') || str_contains($referer, 'q=') || str_contains($referer, '/search')) {
                return 'source_search';
            }
            return 'source_internal';
        }

        return 'source_direct';
    }

    public static function isUniqueHit(string $subjectType, int $subjectId): bool
    {
        $date = now()->toDateString();
        $sessionKey = "uv_{$date}_{$subjectType}_{$subjectId}";
        if (session()->has($sessionKey)) {
            return false;
        }

        $ip = (string) request()->ip();
        $cacheKey = "uv_{$date}_{$subjectType}_{$subjectId}_" . sha1($ip);
        if (Cache::has($cacheKey)) {
            session()->put($sessionKey, 1);
            return false;
        }

        Cache::put($cacheKey, 1, now()->endOfDay());
        session()->put($sessionKey, 1);
        return true;
    }

    public static function recordDailyView(string $subjectType, int $subjectId, bool $isUnique, ?string $source = null): void
    {
        try {
            if (!Schema::hasTable('daily_views')) {
                return;
            }

            $today = now()->toDateString();
            $now = now();

            DB::table('daily_views')->insertOrIgnore([
                'view_date' => $today,
                'subject_type' => $subjectType,
                'subject_id' => $subjectId,
                'total_views' => 0,
                'unique_views' => 0,
                'source_google' => 0,
                'source_search' => 0,
                'source_direct' => 0,
                'source_internal' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $updates = [
                'total_views' => DB::raw('total_views + 1'),
                'updated_at' => $now,
            ];
            if ($isUnique) {
                $updates['unique_views'] = DB::raw('unique_views + 1');
            }
            if ($source && in_array($source, ['source_google', 'source_search', 'source_direct', 'source_internal'], true)) {
                $updates[$source] = DB::raw("{$source} + 1");
            }

            DB::table('daily_views')
                ->where('view_date', $today)
                ->where('subject_type', $subjectType)
                ->where('subject_id', $subjectId)
                ->update($updates);
        } catch (\Throwable $e) {
            // Silently ignore if table is in transient state
        }
    }

    public static function trackSubject(string $subjectType, int $subjectId = 0): bool
    {
        if (self::isBot()) {
            return false;
        }

        $isUnique = self::isUniqueHit($subjectType, $subjectId);
        $source = self::detectSource();
        self::recordDailyView($subjectType, $subjectId, $isUnique, $source);

        return $isUnique;
    }

    public static function track(Model $model, string $type, ?int $ownerId = null): bool
    {
        if (!$model->exists || self::isBot()) {
            return false;
        }

        // Exclude owner/creator views
        if ($ownerId && auth()->check() && auth()->id() === $ownerId) {
            return false;
        }

        $entityId = (int) $model->getKey();
        $isUnique = self::isUniqueHit($type, $entityId);
        $source = self::detectSource();

        // Always record daily rollup (total + unique + source)
        self::recordDailyView($type, $entityId, $isUnique, $source);

        // Also attribute category & province views when viewing a company
        if ($type === 'company') {
            if (!empty($model->category_id)) {
                self::trackSubject('category', (int) $model->category_id);
            }
            if (!empty($model->province_id)) {
                self::trackSubject('province', (int) $model->province_id);
            }
        }

        if (!$isUnique) {
            return false;
        }

        try {
            if (Schema::hasColumn($model->getTable(), 'views_count')) {
                $model->increment('views_count');
            }
        } catch (\Throwable $e) {
            // Silently ignore
        }

        return true;
    }

    public static function trackInteraction(int $companyId, string $channel): bool
    {
        if ($companyId <= 0 || self::isBot()) {
            return false;
        }

        $columnMap = [
            'phone' => 'phone_clicks',
            'whatsapp' => 'whatsapp_clicks',
            'website' => 'website_clicks',
            'bookmark' => 'bookmark_clicks',
        ];
        if (!isset($columnMap[$channel])) {
            return false;
        }

        // Exclude owner from inflating their own lead stats
        try {
            $ownerId = DB::table('companies')->where('id', $companyId)->value('user_id');
            if ($ownerId && auth()->check() && (int) auth()->id() === (int) $ownerId) {
                return false;
            }
        } catch (\Throwable $e) {}

        // Throttle duplicate rapid clicks per visitor for 5 minutes
        $ip = (string) request()->ip();
        $cacheKey = "ci_{$companyId}_{$channel}_" . sha1($ip);
        if (Cache::has($cacheKey)) {
            return false;
        }
        Cache::put($cacheKey, 1, now()->addMinutes(5));

        try {
            if (!Schema::hasTable('company_interactions')) {
                return false;
            }

            $today = now()->toDateString();
            $now = now();

            DB::table('company_interactions')->insertOrIgnore([
                'stat_date' => $today,
                'company_id' => $companyId,
                'phone_clicks' => 0,
                'whatsapp_clicks' => 0,
                'website_clicks' => 0,
                'bookmark_clicks' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $col = $columnMap[$channel];
            DB::table('company_interactions')
                ->where('stat_date', $today)
                ->where('company_id', $companyId)
                ->update([
                    $col => DB::raw("{$col} + 1"),
                    'updated_at' => $now,
                ]);

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public static function formatShortPersianDate(\Carbon\Carbon $date): string
    {
        if (class_exists(\IntlDateFormatter::class)) {
            $fmt = new \IntlDateFormatter(
                'fa_IR@calendar=persian',
                \IntlDateFormatter::NONE,
                \IntlDateFormatter::NONE,
                'Asia/Tehran',
                \IntlDateFormatter::TRADITIONAL,
                'd MMM'
            );
            $res = $fmt->format($date->timestamp);
            if (is_string($res) && $res !== '') {
                return $res;
            }
        }
        return $date->format('m/d');
    }

    /**
     * Build a continuous N-day series of [labels, total, unique] for given subject(s).
     */
    public static function getDailySeries(string $subjectType, int|array $subjectIds, int $days = 30): array
    {
        $ids = is_array($subjectIds) ? array_values(array_filter($subjectIds, fn ($v) => $v !== null)) : [$subjectIds];
        $start = now()->subDays($days - 1)->startOfDay();
        $rows = collect();

        try {
            if (Schema::hasTable('daily_views') && !empty($ids)) {
                $rows = DB::table('daily_views')
                    ->selectRaw('view_date, SUM(total_views) as total_views, SUM(unique_views) as unique_views')
                    ->where('subject_type', $subjectType)
                    ->whereIn('subject_id', $ids)
                    ->where('view_date', '>=', $start->toDateString())
                    ->groupBy('view_date')
                    ->get()
                    ->keyBy('view_date');
            }
        } catch (\Throwable $e) {}

        $labels = [];
        $total = [];
        $unique = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $d = now()->subDays($i);
            $key = $d->toDateString();
            $labels[] = self::formatShortPersianDate($d);
            $row = $rows->get($key);
            $total[] = $row ? (int) $row->total_views : 0;
            $unique[] = $row ? (int) $row->unique_views : 0;
        }

        return compact('labels', 'total', 'unique');
    }
}
