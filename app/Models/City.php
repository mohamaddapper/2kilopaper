<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'province_id',
        'name',
        'slug',
        'term_id',
        'description',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function companies()
    {
        return $this->hasMany(Company::class);
    }
}
