<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['phone', 'name', 'is_admin'];
    protected $hidden = ['remember_token'];
    protected $casts = [
        'is_admin' => 'boolean',
    ];

    public function getAuthPassword(): string
    {
        return '';
    }

    public function isAdmin(): bool
    {
        return (bool) ($this->is_admin || $this->id === 1);
    }

    public function companies() { return $this->hasMany(Company::class); }
    public function jobPostings() { return $this->hasMany(JobPosting::class); }
    public function jobApplications() { return $this->hasMany(JobApplication::class); }
}
