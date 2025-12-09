<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TelegramUser extends Model
{
    protected $fillable = [
        'telegram_id',
        'username',
        'first_name',
        'last_name',
        'language_code',
        'avatar_path',
        'last_activity',
        'state',
        'state_data',
    ];

    public function broadcastMessages(): BelongsToMany
    {
        return $this->belongsToMany(
            BroadcastMessage::class,   // связанная модель
            'broadcast_message_user'   // промежуточная таблица
        );
    }
}
