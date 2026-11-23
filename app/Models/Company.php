<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Company extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'province_id',
        'city_id',
        'name',
        'slug',
        'status',
        'tier',
        'slogan',
        'description',
        'phone',
        'website',
        'address',
        'locations',
        'factory_province',
        'factory_city',
        'factory_address',
        'office_province',
        'office_city',
        'office_address',
        'phones',
        'contacts',
        'tech_specs',
        'activity_types',
        'services',
        'logo',
        'cover',
        'catalog',
        'gallery',
        'video_code',
        'socials',
    ];

    protected $casts = [
        'tier_expires_at' => 'datetime',
        'phones' => 'array',
        'contacts' => 'array',
        'tech_specs' => 'array',
        'activity_types' => 'array',
        'services' => 'array',
        'gallery' => 'array',
        'socials' => 'array',
        'locations' => 'array',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function categories() { return $this->belongsToMany(Category::class, 'company_categories')->withPivot('is_primary'); }
    public function province() { return $this->belongsTo(Province::class); }
    public function city() { return $this->belongsTo(City::class); }
    public function getRouteKeyName() { return 'slug'; }

    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        $field = $field ?? $this->getRouteKeyName();
        return $query->where(function ($q) use ($field, $value) {
            $q->where($field, urldecode((string) $value))
              ->orWhere($field, (string) $value);
        });
    }

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

    public static function locationTypes(): array
    {
        return [
            'factory' => ['title' => 'کارخانه / کارگاه تولیدی', 'icon' => '🏭'],
            'central_office' => ['title' => 'دفتر مرکزی', 'icon' => '🏢'],
            'sales_office' => ['title' => 'دفتر فروش / بازرگانی', 'icon' => '💼'],
            'store' => ['title' => 'فروشگاه / مغازه / شوروم', 'icon' => '🏪'],
            'warehouse' => ['title' => 'انبار مرکزی / بارانداز', 'icon' => '📦'],
            'branch' => ['title' => 'شعبه دیگر', 'icon' => '📍'],
        ];
    }

    public static function getLocationTypeInfo(?string $type): array
    {
        $types = static::locationTypes();
        return $types[$type] ?? ['title' => 'موقعیت مکانی', 'icon' => '📍'];
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
