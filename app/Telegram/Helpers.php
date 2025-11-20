<?php

namespace App\Telegram;

use App\Models\TelegramUser;

class Helpers
{
    public static function logUser($user)
    {
        TelegramUser::updateOrCreate(
            ['telegram_id' => $user->id],
            [
                'username'   => $user->username ?? null,
                'first_name' => $user->first_name ?? null,
                'last_name'  => $user->last_name ?? null,
                'language_code'  => $user->languageCode,

            ]
        );
    }
}
