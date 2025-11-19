<?php

namespace App\Telegram;

class KeyboardBuilder
{
    public static function paginate($items, $callbackPrefix, $page, $perPage = 10)
    {
        $keyboard = [];

        foreach ($items as $item) {
            $keyboard[] = [
                ['text' => $item->name, 'callback_data' => "{$callbackPrefix}:{$item->id}"]
            ];
        }

        // Pagination buttons
        $keyboard[] = array_filter([
            $page > 1 ? ['text' => '⬅️', 'callback_data' => "{$callbackPrefix}:page:" . ($page - 1)] : null,
            count($items) === $perPage ? ['text' => '➡️', 'callback_data' => "{$callbackPrefix}:page:" . ($page + 1)] : null,
        ]);

        return json_encode(['inline_keyboard' => $keyboard]);
    }
}
