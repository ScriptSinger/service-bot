<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'device_type_id',
        'name',
        'slug',
        'country',
    ];

    public function deviceType()
    {
        return $this->belongsTo(DeviceType::class);
    }

    public function deviceModels()
    {
        return $this->hasMany(DeviceModel::class);
    }
}
