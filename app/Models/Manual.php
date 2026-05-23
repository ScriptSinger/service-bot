<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manual extends Model
{
    protected $fillable = [
        'device_model_id'
    ];

    public function deviceModel()
    {
        return $this->belongsTo(DeviceModel::class, 'device_model_id');
    }

    public function files()
    {
        return $this->hasMany(ManualFile::class);
    }

    public function getBrandNameAttribute(): ?string
    {
        return $this->deviceModel?->brand?->name;
    }

    public function getDisplayNameAttribute(): string
    {
        $parts = array_values(array_filter([
            $this->brand_name,
            $this->deviceModel?->name,
        ]));

        if ($parts !== []) {
            return implode(' / ', $parts);
        }

        return sprintf('Manual #%d', $this->getKey());
    }
}
