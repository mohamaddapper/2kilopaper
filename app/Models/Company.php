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
        'views_count',
        'tier',
        'slogan',
        'description',
        'phone',
        'email',
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
    public function jobPostings() { return $this->hasMany(JobPosting::class); }
    public function bookmarks() { return $this->hasMany(CompanyBookmark::class); }

    public function isBookmarkedBy(?User $user): bool
    {
        if (!$user) return false;
        return $this->bookmarks()->where('user_id', $user->id)->exists();
    }

    public function getActivityRoleAttribute(): string
    {
        // 1. بررسی فیلد activity_types در صورت وجود
        $actTypes = (array) ($this->activity_types ?? []);
        foreach ($actTypes as $type) {
            $typeStr = mb_strtolower(trim($type));
            if (str_contains($typeStr, 'تولید') || in_array($typeStr, ['manufacturer', 'factory', 'producer'])) {
                return 'تولیدکننده';
            }
            if (str_contains($typeStr, 'وارد') || in_array($typeStr, ['importer'])) {
                return 'واردکننده';
            }
            if (str_contains($typeStr, 'فروش') || str_contains($typeStr, 'تأمین') || in_array($typeStr, ['seller', 'store', 'supplier'])) {
                return 'فروشنده';
            }
            if (str_contains($typeStr, 'چاپ') || in_array($typeStr, ['printer', 'printing'])) {
                return 'چاپخانه';
            }
            if (str_contains($typeStr, 'خدمات') || in_array($typeStr, ['services', 'service'])) {
                return 'خدمات‌دهنده';
            }
        }

        // 2. استخراج از درخت دسته‌بندی اصلی یا دسته‌بندی‌های شرکت
        $cat = $this->category;
        if (!$cat && $this->relationLoaded('categories')) {
            $cat = $this->categories->firstWhere('pivot.is_primary', 1) ?: $this->categories->first();
        }
        if (!$cat && $this->category_id) {
            $cat = Category::find($this->category_id);
        }

        if ($cat) {
            $curr = $cat;
            while ($curr->parent_id) {
                $curr = $curr->parent ?: Category::find($curr->parent_id);
                if (!$curr) break;
            }

            if ($curr) {
                return match ($curr->slug) {
                    'factories' => 'تولیدکننده',
                    'importers' => 'واردکننده',
                    'stores' => 'فروشنده',
                    'printing-services' => 'چاپخانه',
                    'service-providers' => 'خدمات‌دهنده',
                    default => 'شرکت',
                };
            }
        }

        return 'شرکت';
    }

    public function getMainCategorySlugAttribute(): string
    {
        $cat = $this->category;
        if (!$cat && $this->relationLoaded('categories')) {
            $cat = $this->categories->firstWhere('pivot.is_primary', 1) ?: $this->categories->first();
        }
        if (!$cat && $this->category_id) {
            $cat = Category::find($this->category_id);
        }

        if (!$cat) {
            return 'عمومی';
        }

        if (!$cat->parent_id) {
            return 'صنایع-سلولزی';
        }

        return slugify($cat->name) ?: 'عمومی';
    }

    public function routeParams(): array
    {
        return [
            'role' => $this->activity_role,
            'category' => $this->main_category_slug,
            'company' => $this->slug,
        ];
    }

    public function getUrlAttribute(): string
    {
        return route('company', $this->routeParams());
    }

    /**
     * دسته‌بندی‌های شرکت را بر اساس نقش (تولیدکننده، واردکننده، فروشنده، چاپخانه، خدمات‌دهنده) گروه‌بندی می‌کند.
     */
    public function groupedCategories(): array
    {
        $roleMeta = [
            'factories' => [
                'label' => 'تولیدکننده',
                'icon' => svg_icon('factory', 14),
                'bg' => '#ecfdf5',
                'border' => '#a7f3d0',
                'color' => '#047857',
                'order' => 1,
            ],
            'importers' => [
                'label' => 'واردکننده',
                'icon' => svg_icon('ship', 14),
                'bg' => '#eef2ff',
                'border' => '#c7d2fe',
                'color' => '#4338ca',
                'order' => 2,
            ],
            'stores' => [
                'label' => 'فروشنده',
                'icon' => svg_icon('store', 14),
                'bg' => '#fffbeb',
                'border' => '#fde68a',
                'color' => '#b45309',
                'order' => 3,
            ],
            'printing-services' => [
                'label' => 'چاپخانه',
                'icon' => svg_icon('printer', 14),
                'bg' => '#f0f9ff',
                'border' => '#bae6fd',
                'color' => '#0369a1',
                'order' => 4,
            ],
            'service-providers' => [
                'label' => 'خدمات‌دهنده',
                'icon' => svg_icon('settings', 14),
                'bg' => '#f0fdfa',
                'border' => '#99f6e4',
                'color' => '#0f766e',
                'order' => 5,
            ],
            'other' => [
                'label' => 'سایر فعالیت‌ها',
                'icon' => svg_icon('tag', 14),
                'bg' => '#f8fafc',
                'border' => '#cbd5e1',
                'color' => '#334155',
                'order' => 6,
            ],
        ];

        $allCats = collect();
        if ($this->category) {
            $allCats->push($this->category);
        }
        if ($this->relationLoaded('categories')) {
            foreach ($this->categories as $cat) {
                $allCats->push($cat);
            }
        } elseif ($this->exists) {
            foreach ($this->categories()->with('parent.parent.parent')->get() as $cat) {
                $allCats->push($cat);
            }
        }

        $allCats = $allCats->unique('id')->values();
        $groups = [];

        foreach ($allCats as $cat) {
            $curr = $cat;
            while ($curr->parent_id) {
                $curr = $curr->parent ?: Category::find($curr->parent_id);
                if (!$curr) break;
            }

            $rootSlug = ($curr && isset($roleMeta[$curr->slug])) ? $curr->slug : 'other';
            if (!isset($groups[$rootSlug])) {
                $groups[$rootSlug] = array_merge($roleMeta[$rootSlug], [
                    'slug' => $rootSlug,
                    'items' => [],
                ]);
            }

            $groups[$rootSlug]['items'][] = [
                'id' => $cat->id,
                'name' => $cat->name,
                'url' => $cat->url,
                'is_primary' => ((int) $cat->id === (int) $this->category_id),
            ];
        }

        foreach ($groups as &$g) {
            usort($g['items'], fn($a, $b) => ($b['is_primary'] <=> $a['is_primary']));
        }
        unset($g);

        uasort($groups, function ($a, $b) {
            $aPrimary = collect($a['items'])->contains('is_primary', true) ? 0 : 1;
            $bPrimary = collect($b['items'])->contains('is_primary', true) ? 0 : 1;
            if ($aPrimary !== $bPrimary) {
                return $aPrimary <=> $bPrimary;
            }
            return $a['order'] <=> $b['order'];
        });

        return array_values($groups);
    }

    public function getRouteKeyName() { return 'slug'; }

    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        $field = $field ?? $this->getRouteKeyName();
        $raw = (string) $value;
        $decoded = urldecode($raw);
        $doubleDecoded = urldecode($decoded);

        return $query->where(function ($q) use ($field, $raw, $decoded, $doubleDecoded) {
            $q->where($field, $raw)
              ->orWhere($field, $decoded)
              ->orWhere($field, $doubleDecoded);
            if (is_numeric($raw)) {
                $q->orWhere('id', (int) $raw);
            }
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
            'factory' => ['title' => 'کارخانه / کارگاه تولیدی', 'icon' => svg_icon('factory', 15)],
            'central_office' => ['title' => 'دفتر مرکزی', 'icon' => svg_icon('building', 15)],
            'sales_office' => ['title' => 'دفتر فروش / بازرگانی', 'icon' => svg_icon('briefcase', 15)],
            'store' => ['title' => 'فروشگاه / مغازه / شوروم', 'icon' => svg_icon('store', 15)],
            'warehouse' => ['title' => 'انبار مرکزی / بارانداز', 'icon' => svg_icon('box', 15)],
            'branch' => ['title' => 'شعبه دیگر', 'icon' => svg_icon('map-pin', 15)],
        ];
    }

    public static function getLocationTypeInfo(?string $type): array
    {
        $types = static::locationTypes();
        return $types[$type] ?? ['title' => 'موقعیت مکانی', 'icon' => svg_icon('map-pin', 15)];
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
