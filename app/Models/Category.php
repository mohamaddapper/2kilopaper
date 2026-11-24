<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'term_id',
        'icon',
        'description',
        'seo_title',
        'seo_description',
        'faqs',
        'sort',
    ];

    protected $casts = [
        'faqs' => 'array',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort');
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_categories')->withPivot('is_primary');
    }

    public static function roleMap(): array
    {
        return [
            'factories' => ['slug' => 'تولیدکننده', 'plural' => 'تولیدکنندگان', 'icon' => svg_icon('factory', 15)],
            'importers' => ['slug' => 'واردکننده', 'plural' => 'واردکنندگان', 'icon' => svg_icon('ship', 15)],
            'stores' => ['slug' => 'فروشنده', 'plural' => 'فروشندگان', 'icon' => svg_icon('store', 15)],
            'printing-services' => ['slug' => 'چاپخانه', 'plural' => 'چاپخانه‌ها و مجتمع‌های چاپ', 'icon' => svg_icon('printer', 15)],
            'service-providers' => ['slug' => 'خدمات‌دهنده', 'plural' => 'خدمات‌دهندگان فنی و تکمیلی', 'icon' => svg_icon('settings', 15)],
        ];
    }

    public function rootPillar(): Category
    {
        $curr = $this;
        while ($curr->parent_id) {
            $next = $curr->parent ?: static::find($curr->parent_id);
            if (!$next) break;
            $curr = $next;
        }
        return $curr;
    }

    public function getRoleSlugAttribute(): string
    {
        $root = $this->rootPillar();
        return static::roleMap()[$root->slug]['slug'] ?? 'صنعت';
    }

    public function getRolePluralAttribute(): string
    {
        $root = $this->rootPillar();
        return static::roleMap()[$root->slug]['plural'] ?? $root->name;
    }

    public function getRoleIconAttribute(): string
    {
        $root = $this->rootPillar();
        return static::roleMap()[$root->slug]['icon'] ?? svg_icon('box', 15);
    }

    public function getUrlAttribute(): string
    {
        if (!$this->parent_id) {
            return route('categories.role', ['role' => $this->role_slug]);
        }

        return route('categories.show', [
            'role' => $this->role_slug,
            'category' => slugify($this->name),
        ]);
    }

    public function getSmartH1Attribute(): string
    {
        if (!$this->parent_id) {
            return $this->name;
        }

        $rootSlug = $this->rootPillar()->slug;
        if ($rootSlug === 'printing-services') {
            return str_contains($this->name, 'چاپ') ? $this->name : ('خدمات چاپ ' . $this->name);
        }
        if ($rootSlug === 'service-providers') {
            return str_contains($this->name, 'خدمات') ? $this->name : ('خدمات ' . $this->name);
        }

        return $this->role_plural . ' ' . $this->name;
    }

    public function getSmartSeoTitleAttribute(): string
    {
        if (!empty($this->seo_title)) {
            return $this->seo_title;
        }

        return $this->smart_h1 . ' | لیست کارخانه‌ها و تلفن تماس';
    }

    public function getCleanDescriptionAttribute(): string
    {
        $raw = (string) ($this->description ?? '');
        if ($raw === '') {
            return '';
        }

        // حذف شورت‌کدهای قدیمی وردپرس مانند [divider height="1px"]
        $cleaned = preg_replace('/\[[^\]]+\]/u', '', $raw);
        $cleaned = trim((string) $cleaned);
        if ($cleaned === '') {
            return '';
        }

        // اگر فقط یک خط کوتاه تکراری مثل "فروشندگان کاغذ تست لاینر" بود، در ویو مدیریت می‌شود
        $cleaned = strip_tags($cleaned, '<p><br><strong><b><em><ul><ol><li><a><h2><h3><h4>');
        // تبدیل خطوط جدید به پاراگراف در صورت نبود تگ <p>
        if (!str_contains($cleaned, '<p')) {
            $lines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $cleaned))));
            if (count($lines) > 1) {
                // اگر خط اول دقیقاً عنوان تکراری کوتاه است، آن را به عنوان تیتر یا جدا نگه داریم
                $cleaned = '<p>' . implode('</p><p>', $lines) . '</p>';
            }
        }

        return $cleaned;
    }

    public function getSmartMetaDescriptionAttribute(): string
    {
        if (!empty($this->seo_description)) {
            return $this->seo_description;
        }

        $spaced = preg_replace('/<\/?(p|br|div|li|h[1-6])[^>]*>/i', ' ', $this->clean_description);
        $text = trim(preg_replace('/\s+/u', ' ', strip_tags((string) $spaced)));
        if (mb_strlen($text) > 40) {
            return Str::limit($text, 155);
        }

        return "فهرست کامل {$this->smart_h1} در سراسر ایران به همراه آدرس، شماره تماس مستقیم، محصولات و اطلاعات ثبت‌شده در مرجع ۲ کیلو کاغذ.";
    }

    public function allDescendantIds(): array
    {
        $ids = [$this->id];
        $children = $this->children()->with('children')->get();
        foreach ($children as $child) {
            $ids[] = $child->id;
            foreach ($child->children as $grandChild) {
                $ids[] = $grandChild->id;
            }
        }
        return array_values(array_unique($ids));
    }
}
