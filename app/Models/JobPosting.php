<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JobPosting extends Model
{
    protected $fillable = [
        'user_id',
        'company_id',
        'title',
        'slug',
        'category_id',
        'job_type',
        'work_shift',
        'gender',
        'military_service',
        'experience_years',
        'salary_type',
        'salary_min',
        'salary_max',
        'province_id',
        'city_id',
        'workplace_type',
        'address',
        'benefits',
        'skills',
        'description',
        'requirements',
        'contact_type',
        'contact_phone',
        'contact_whatsapp',
        'status',
        'views_count',
        'is_featured',
        'is_immediate',
        'expires_at',
    ];

    protected $casts = [
        'benefits' => 'array',
        'skills' => 'array',
        'expires_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_immediate' => 'boolean',
    ];

    public static function makeSlug(string $title): string
    {
        $base = preg_replace('/[^\p{L}\p{N}]+/u', '-', mb_strtolower(trim($title)));
        $base = trim($base, '-');
        if (empty($base)) {
            $base = 'job-' . uniqid();
        }
        $slug = $base;
        $counter = 1;
        while (self::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $counter++;
        }
        return $slug;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class)->latest();
    }

    public function getJobTypeLabelAttribute(): string
    {
        return match ($this->job_type) {
            'full_time' => 'تمام‌وقت',
            'part_time' => 'پاره‌وقت',
            'shift' => 'شیفتی / نوبت‌کاری',
            'project' => 'پروژه‌ای',
            'internship' => 'کارآموزی',
            default => 'تمام‌وقت',
        };
    }

    public function getWorkShiftLabelAttribute(): ?string
    {
        return match ($this->work_shift) {
            'day' => 'تک شیفت روز (عادی)',
            'rotational' => 'دو شیفت چرخشی',
            'night' => 'شیفت شب',
            'shift' => 'نوبت‌کاری گردشی',
            default => null,
        };
    }

    public function getWorkplaceTypeLabelAttribute(): string
    {
        return match ($this->workplace_type) {
            'factory' => 'کارخانه / کارگاه',
            'office' => 'دفتر مرکزی',
            'store' => 'فروشگاه / انبار',
            default => 'محل کار',
        };
    }

    public function getGenderLabelAttribute(): string
    {
        return match ($this->gender) {
            'male' => 'آقا',
            'female' => 'خانم',
            default => 'فرقی ندارد',
        };
    }

    public function getExperienceLabelAttribute(): string
    {
        return match ($this->experience_years) {
            '0' => 'بدون نیاز به سابقه',
            '1-3' => '۱ تا ۳ سال',
            '3-6' => '۳ تا ۶ سال',
            '6+' => 'بیش از ۶ سال',
            default => 'مهم نیست',
        };
    }

    public function getSalaryLabelAttribute(): string
    {
        if ($this->salary_type === 'ministry_plus') {
            return 'قانون کار + پاداش تولید';
        }
        if ($this->salary_type === 'range' && ($this->salary_min || $this->salary_max)) {
            if ($this->salary_min && $this->salary_max) {
                return number_format($this->salary_min) . ' تا ' . number_format($this->salary_max) . ' تومان';
            }
            if ($this->salary_min) {
                return 'از ' . number_format($this->salary_min) . ' تومان به بالا';
            }
            return 'تا ' . number_format($this->salary_max) . ' تومان';
        }
        return 'توافقی';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
