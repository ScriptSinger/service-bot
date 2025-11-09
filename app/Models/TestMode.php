<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestMode extends Model
{
    protected $fillable = [
        'device_model_id',
        'entry_combination',
        'exit_combination',
        'notes',
        'image_url',
    ];

    public function deviceModel()
    {
        return $this->belongsTo(DeviceModel::class, 'device_model_id');
    }
}
