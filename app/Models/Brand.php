<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use Sluggable;

    protected $fillable = [
        'device_type_id',
        'name',
        'slug',
        'country',
    ];

    public function deviceTypes()
    {
        return $this->belongsToMany(DeviceType::class);
    }

    public function deviceModels()
    {
        return $this->hasMany(DeviceModel::class);
    }


    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'  // поле, из которого генерируем slug
            ]
        ];
    }
}
