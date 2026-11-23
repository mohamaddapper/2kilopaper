<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Company extends Model
{
    protected $fillable = ['category_id', 'city_id', 'name', 'description', 'phone', 'website', 'address'];
    protected $casts = ['tier_expires_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function city() { return $this->belongsTo(City::class); }
    public function getRouteKeyName() { return 'slug'; }

    public function scopeApproved(Builder $q) { return $q->where('status', 'approved'); }

    /** ویژه‌ها (با اشتراک فعال) اول، سپس جدیدترها */
public function scopeRanked(Builder $q)
{
    return $q->orderByRaw(
        "CASE WHEN tier IS NOT NULL AND (tier_expires_at IS NULL OR tier_expires_at > ?)
         THEN CASE tier WHEN 'gold' THEN 0 WHEN 'silver' THEN 1 ELSE 2 END ELSE 3 END",
        [now()->format('Y-m-d H:i:s')]
    )->orderByDesc('id');
}

    public function activeTier(): ?string
    {
        if (!$this->tier) return null;
        return (!$this->tier_expires_at || $this->tier_expires_at->isFuture()) ? $this->tier : null;
    }

    public static function makeSlug(string $name): string
    {
        $base = Str::limit(slugify($name), 60, '');
        $slug = $base;
        while (static::where('slug', $slug)->exists()) {
            $slug = $base . '-' . random_int(100, 99999);
        }
        return $slug;
    }
}
