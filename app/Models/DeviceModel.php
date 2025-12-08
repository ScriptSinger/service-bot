<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceModel extends Model
{
    protected $fillable = [
        'brand_id',
        'name',
        'description',
        'year_from',
        'year_to',
        'image_url',
        'active',
    ];


    public function deviceType()
    {
        return $this->belongsTo(DeviceType::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function manuals()
    {
        return $this->hasMany(Manual::class, 'device_model_id');
    }

    public function testModes()
    {
        return $this->hasMany(TestMode::class, 'device_model_id');
    }

    public function errorCodes()
    {
        return $this->hasMany(ErrorCode::class, 'device_model_id');
    }
}
