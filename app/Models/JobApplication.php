<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'job_posting_id',
        'user_id',
        'full_name',
        'phone',
        'email',
        'birth_year',
        'gender',
        'military_status',
        'province_id',
        'city_id',
        'education',
        'experience_years',
        'last_company',
        'skills',
        'expected_salary',
        'resume_file',
        'cover_letter',
        'status',
        'employer_note',
        'interview_at',
    ];

    protected $casts = [
        'skills' => 'array',
        'interview_at' => 'datetime',
    ];

    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class);
    }

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

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'new' => 'جدید',
            'reviewed' => 'بررسی‌شده',
            'interview' => 'دعوت به مصاحبه',
            'rejected' => 'ردشده',
            'hired' => 'استخدام‌شده',
            default => 'نامشخص',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'new' => 'silver',
            'reviewed' => 'warn',
            'interview' => 'ok',
            'rejected' => 'bad',
            'hired' => 'gold',
            default => '',
        };
    }
}
