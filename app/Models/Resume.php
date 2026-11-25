<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    public const SPECIALTIES = [
        'اپراتور خط تولید ورق (کروگیت)',
        'اپراتور چاپ افست',
        'اپراتور چاپ فلکسو',
        'اپراتور دایکات و تیغ‌زنی',
        'اپراتور لمینت و لب‌چسب',
        'طراح گرافیک و قالب‌سازی بسته‌بندی',
        'مدیر تولید / سرپرست سالن',
        'کارشناس کنترل کیفیت (QC)',
        'کارشناس فروش کارتن و مقوا',
        'انباردار / راننده لیفتراک',
        'تعمیرکار و فنی دستگاه‌ها',
        'کارگر فنی / بسته‌بندی',
        'سایر تخصص‌ها',
    ];

    public const MACHINE_SKILLS = [
        'خط ورق‌سازی ۳ و ۵ لایه',
        'چاپ افست (هایدلبرگ / رولند)',
        'چاپ فلکسو ۱ تا ۴ رنگ',
        'دایکات فکی / اتومات',
        'لمینت اتومات / دستی',
        'لب‌چسب و منگنه‌زن',
        'چاپ روتوگراور / هلیو',
        'لیفتراک و جک پالت',
        'طراحی Corel / Illustrator / ArtiosCAD',
        'فروش B2B کارتن، ورق و مقوا',
    ];

    public const WORK_TYPES = [
        'full_time' => 'تمام‌وقت',
        'shift_work' => 'شیفتی (روز / شب)',
        'part_time' => 'پاره‌وقت',
        'project' => 'پروژه‌ای / مشاوره‌ای',
    ];

    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'job_title',
        'specialty',
        'machines',
        'province_id',
        'city_id',
        'experience_years',
        'work_type',
        'needs_accommodation',
        'expected_salary',
        'bio',
        'resume_file',
        'availability',
        'status',
        'views_count',
    ];

    protected $casts = [
        'machines' => 'array',
        'needs_accommodation' => 'boolean',
        'experience_years' => 'integer',
        'views_count' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function scopeApprovedPublic(Builder $q): Builder
    {
        return $q->where('status', 'approved')->where('availability', 'available');
    }

    public function workTypeLabel(): string
    {
        return self::WORK_TYPES[$this->work_type] ?? 'تمام‌وقت';
    }

    public static function viewerCanSeeContact(?User $user): bool
    {
        if (!$user) {
            return false;
        }
        if ($user->isAdmin()) {
            return true;
        }

        return $user->companies()->get()->contains(fn (Company $c) => $c->hasActiveBenefits());
    }
}
