<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManualFile extends Model
{
    protected $fillable = [
        'manual_id',
        'title',
        'description',
        'file_url',
    ];

    public function manual()
    {
        return $this->belongsTo(Manual::class);
    }
}
