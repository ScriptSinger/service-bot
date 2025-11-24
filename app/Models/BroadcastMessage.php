<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BroadcastMessage extends Model
{
    protected $fillable = [
        'message',
        'status',
        'receivers_count',
        'result',
    ];

    protected $casts = [
        'result' => 'array',
    ];

    protected $attributes = [
        'status' => 'draft',
    ];

    public function telegramUsers(): BelongsToMany
    {
        return $this->belongsToMany(
            TelegramUser::class,       // связанная модель
            'broadcast_message_user'   // промежуточная таблица
        );
    }
}
