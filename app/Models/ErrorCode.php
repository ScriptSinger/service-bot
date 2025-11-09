<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorCode extends Model
{
    protected $fillable = [
        'device_model_id',
        'code',
        'description',
        'severity',
    ];

    protected $casts = [
        'severity' => 'string',
    ];

    public function deviceModel()
    {
        return $this->belongsTo(DeviceModel::class, 'device_model_id');
    }
}
