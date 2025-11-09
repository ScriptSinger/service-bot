<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceType extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    public function brands()
    {
        return $this->hasMany(Brand::class);
    }
}
