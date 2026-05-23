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

    public function getFileNameAttribute(): string
    {
        if (! is_string($this->file_url) || $this->file_url === '') {
            return '';
        }

        return basename($this->file_url);
    }
}
