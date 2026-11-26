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
        'admin_note',
        'views_count',
        'tier',
        'tier_expires_at',
        'subscription_plan',
        'subscription_expires_at',
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
        'subscription_expires_at' => 'datetime',
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
    public function affiliates() { return $this->belongsToMany(Company::class, 'company_affiliates', 'company_id', 'affiliate_id'); }

    /**
     * همگام‌سازی دوطرفه (و در صورت نیاز گروهی) شرکت‌های وابسته
     */
    public function syncAffiliates(array $affiliateIds, bool $groupSync = true): void
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('company_affiliates')) {
            return;
        }

        $targetIds = array_values(array_unique(array_filter(array_map('intval', $affiliateIds), fn($id) => $id > 0 && $id !== $this->id)));

        $existingIds = \Illuminate\Support\Facades\DB::table('company_affiliates')
            ->where('company_id', $this->id)
            ->pluck('affiliate_id')
            ->map(fn($v) => (int) $v)
            ->all();

        $removedIds = array_diff($existingIds, $targetIds);
        if (!empty($removedIds)) {
            \Illuminate\Support\Facades\DB::table('company_affiliates')
                ->where('company_id', $this->id)
                ->whereIn('affiliate_id', $removedIds)
                ->delete();
            \Illuminate\Support\Facades\DB::table('company_affiliates')
                ->whereIn('company_id', $removedIds)
                ->where('affiliate_id', $this->id)
                ->delete();
        }

        if (empty($targetIds)) {
            return;
        }

        $now = now();
        $pairs = [];
        $group = $groupSync ? array_values(array_unique(array_merge([$this->id], $targetIds))) : null;

        if ($group !== null) {
            foreach ($group as $a) {
                foreach ($group as $b) {
                    if ($a === $b) {
                        continue;
                    }
                    $pairs[] = ['company_id' => $a, 'affiliate_id' => $b, 'created_at' => $now, 'updated_at' => $now];
                }
            }
        } else {
            foreach ($targetIds as $tid) {
                $pairs[] = ['company_id' => $this->id, 'affiliate_id' => $tid, 'created_at' => $now, 'updated_at' => $now];
                $pairs[] = ['company_id' => $tid, 'affiliate_id' => $this->id, 'created_at' => $now, 'updated_at' => $now];
            }
        }

        foreach (array_chunk($pairs, 200) as $chunk) {
            \Illuminate\Support\Facades\DB::table('company_affiliates')->insertOrIgnore($chunk);
        }
    }

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

    /**
     * اولویت نمایش:
     * ۱. ویژه طلایی  ۲. ویژه نقره‌ای  ۳. ویژه برنزی
     * ۴. اشتراک ۱ ساله  ۵. اشتراک ۶ ماهه  ۶. اشتراک ۳ ماهه
     * ۷. بقیه شرکت‌ها به صورت تصادفی
     */
    public function scopeRanked(Builder $q)
    {
        $now = now()->format('Y-m-d H:i:s');
        $randFn = $q->getConnection()->getDriverName() === 'sqlite' ? 'RANDOM()' : 'RAND()';

        return $q->orderByRaw(
            "CASE
                WHEN tier = 'gold' AND (tier_expires_at IS NULL OR tier_expires_at > ?) THEN 0
                WHEN tier = 'silver' AND (tier_expires_at IS NULL OR tier_expires_at > ?) THEN 1
                WHEN tier = 'bronze' AND (tier_expires_at IS NULL OR tier_expires_at > ?) THEN 2
                WHEN subscription_plan = '12m' AND (subscription_expires_at IS NULL OR subscription_expires_at > ?) THEN 3
                WHEN subscription_plan = '6m' AND (subscription_expires_at IS NULL OR subscription_expires_at > ?) THEN 4
                WHEN subscription_plan = '3m' AND (subscription_expires_at IS NULL OR subscription_expires_at > ?) THEN 5
                ELSE 6
            END",
            [$now, $now, $now, $now, $now, $now]
        )->orderByRaw($randFn);
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

    public function activeSubscription(): ?string
    {
        if (!$this->subscription_plan) return null;
        return (!$this->subscription_expires_at || $this->subscription_expires_at->isFuture()) ? $this->subscription_plan : null;
    }

    public function subscriptionLabel(?string $plan = null): string
    {
        $p = $plan ?? $this->activeSubscription();
        return match ($p) {
            '12m' => 'اشتراک ۱ ساله',
            '6m' => 'اشتراک ۶ ماهه',
            '3m' => 'اشتراک ۳ ماهه',
            default => 'بدون اشتراک',
        };
    }

    public function hasActiveBenefits(): bool
    {
        return (bool) ($this->activeTier() || $this->activeSubscription());
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
