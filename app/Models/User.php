<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['phone', 'name'];
    protected $hidden = ['remember_token'];

    public function getAuthPassword(): string
    {
        return '';
    }

    public function companies() { return $this->hasMany(Company::class); }
    public function jobPostings() { return $this->hasMany(JobPosting::class); }
    public function jobApplications() { return $this->hasMany(JobApplication::class); }
}
