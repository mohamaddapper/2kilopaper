<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class ViewTracker
{
    /**
     * Track a unique view for any model (Company, JobPosting, JobApplication, etc.)
     *
     * Prevents false/inflated view counts from:
     * - Page refreshes (F5 spamming)
     * - Web crawlers & search engine bots (Googlebot, Bingbot, etc.)
     * - The owner or creator viewing their own profile/ad
     * - Same visitor (IP + Session) within a 24-hour throttle window
     *
     * @param Model $model The Eloquent model
     * @param string $type Entity identifier ('company', 'job', 'application')
     * @param int|null $ownerId User ID of the owner/creator to exclude
     * @return bool True if a new unique view was counted, false otherwise
     */
    public static function track(Model $model, string $type, ?int $ownerId = null): bool
    {
        if (!$model->exists) {
            return false;
        }

        // 1. Exclude owner/creator views (owners shouldn't inflate their own view counts)
        if ($ownerId && auth()->check() && auth()->id() === $ownerId) {
            return false;
        }

        // 2. Filter out search engine crawlers, uptime monitors and bots
        $userAgent = strtolower(request()->userAgent() ?? '');
        $bots = [
            'bot', 'crawl', 'spider', 'slurp', 'mediapartners', 'uptime',
            'feed', 'curl', 'wget', 'lighthouse', 'headless', 'python', 'postman'
        ];
        foreach ($bots as $bot) {
            if (str_contains($userAgent, $bot)) {
                return false;
            }
        }

        $entityId = $model->getKey();
        $sessionKey = "viewed_{$type}_{$entityId}";

        // 3. Check current user session (Fastest check, prevents refresh spamming)
        if (session()->has($sessionKey)) {
            return false;
        }

        // 4. Check IP + Entity in cache with 24-hour expiry
        $ip = request()->ip();
        $cacheKey = "view_throttle_{$type}_{$entityId}_{$ip}";

        if (Cache::has($cacheKey)) {
            session()->put($sessionKey, time());
            return false;
        }

        // 5. Valid unique view! Save markers and increment
        Cache::put($cacheKey, true, now()->addHours(24));
        session()->put($sessionKey, time());

        try {
            if (Schema::hasColumn($model->getTable(), 'views_count')) {
                $model->increment('views_count');
            }
        } catch (\Throwable $e) {
            // Silently ignore if table/column in transient state
        }

        return true;
    }
}
