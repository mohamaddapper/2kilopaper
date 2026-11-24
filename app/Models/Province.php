<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'term_id',
        'description',
        'sort',
    ];

    public function cities()
    {
        return $this->hasMany(City::class)->orderBy('name');
    }

    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    public function getUrlAttribute(): string
    {
        return route('locations.show', ['province' => slugify($this->name)]);
    }
}
