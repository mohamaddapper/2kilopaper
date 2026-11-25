<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public const ALL_PERMISSIONS = [
        'companies' => 'مدیریت شرکت‌ها (تأیید، رد، ویرایش، یادداشت)',
        'companies_tier' => 'تغییر سطح اشتراک شرکت‌ها',
        'companies_delete' => 'حذف دائمی شرکت‌ها',
        'categories' => 'دسته‌بندی و سئو',
        'jobs' => 'آگهی‌های شغلی',
        'applications' => 'رزومه‌ها و متقاضیان',
        'users' => 'مدیریت کاربران و همکاران',
        'analytics' => 'آمار و بازدیدها',
        'logs' => 'گزارش فعالیت همکاران',
    ];

    public const ROLE_PRESETS = [
        'super_admin' => [
            'label' => 'مدیر ارشد (دسترسی کامل)',
            'permissions' => ['companies', 'companies_tier', 'companies_delete', 'categories', 'jobs', 'applications', 'users', 'analytics', 'logs'],
        ],
        'moderator' => [
            'label' => 'ناظر و بررسی محتوا',
            'permissions' => ['companies', 'jobs', 'applications'],
        ],
        'seo' => [
            'label' => 'کارشناس سئو و محتوا',
            'permissions' => ['categories', 'companies'],
        ],
        'sales' => [
            'label' => 'کارشناس فروش و پشتیبانی',
            'permissions' => ['companies', 'companies_tier', 'applications', 'analytics'],
        ],
        'custom' => [
            'label' => 'دسترسی سفارشی',
            'permissions' => [],
        ],
    ];

    protected $fillable = ['phone', 'name', 'is_admin', 'admin_role', 'admin_permissions', 'last_login_at'];
    protected $hidden = ['remember_token'];
    protected $casts = [
        'is_admin' => 'boolean',
        'admin_permissions' => 'array',
        'last_login_at' => 'datetime',
    ];

    public function getAuthPassword(): string
    {
        return '';
    }

    public function isAdmin(): bool
    {
        return (bool) ($this->is_admin || $this->id === 1);
    }

    public function isSuperAdmin(): bool
    {
        if ($this->id === 1) {
            return true;
        }
        return (bool) ($this->is_admin && ($this->admin_role === 'super_admin' || empty($this->admin_role)));
    }

    public function hasPermission(string $permission): bool
    {
        if (!$this->isAdmin()) {
            return false;
        }
        if ($this->isSuperAdmin()) {
            return true;
        }
        $perms = is_array($this->admin_permissions) ? $this->admin_permissions : [];
        if (empty($perms) && $this->admin_role && isset(self::ROLE_PRESETS[$this->admin_role])) {
            $perms = self::ROLE_PRESETS[$this->admin_role]['permissions'];
        }
        return in_array($permission, $perms, true);
    }

    public function roleLabel(): string
    {
        if (!$this->isAdmin()) {
            return 'کاربر عادی';
        }
        if ($this->isSuperAdmin()) {
            return self::ROLE_PRESETS['super_admin']['label'];
        }
        return self::ROLE_PRESETS[$this->admin_role]['label'] ?? 'همکار مدیریت';
    }

    public function companies() { return $this->hasMany(Company::class); }
    public function savedCompanies() { return $this->belongsToMany(Company::class, 'company_bookmarks')->withTimestamps(); }
    public function jobPostings() { return $this->hasMany(JobPosting::class); }
    public function jobApplications() { return $this->hasMany(JobApplication::class); }
    public function adminLogs() { return $this->hasMany(AdminLog::class); }
}
