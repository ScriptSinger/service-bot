<?php

namespace App\Services\Telegram;

use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Collection;

class KeyboardBuilder
{
    /**
     * Создаёт inline-клавиатуру из коллекции моделей
     *
     * @param Collection $items Коллекция Eloquent моделей
     * @param string $callbackPrefix Префикс для callback_data
     * @param string|null $backCallback Префикс callback для кнопки "Назад"
     * @return Keyboard
     */
    public function fromCollection(Collection $items, string $callbackPrefix, ?string $backCallback = null): Keyboard
    {
        $keyboard = Keyboard::make()->inline();

        foreach ($items as $item) {
            $keyboard->row([
                Keyboard::inlineButton([
                    'text' => $item->name ?? $item->title ?? '—',
                    'callback_data' => "{$callbackPrefix}:{$item->id}"
                ])
            ]);
        }

        if ($backCallback) {
            $keyboard->row([
                Keyboard::inlineButton([
                    'text' => 'Назад',
                    'callback_data' => $backCallback
                ])
            ]);
        }

        return $keyboard;
    }

    /**
     * Для пагинации
     */
    public function paginate(Collection $items, string $callbackPrefix, int $page, int $perPage = 10, ?string $backCallback = null): Keyboard
    {
        $keyboard = Keyboard::make()->inline();

        foreach ($items as $item) {
            $keyboard->row([
                Keyboard::inlineButton([
                    'text' => $item->name ?? $item->title ?? '—',
                    'callback_data' => "{$callbackPrefix}:{$item->id}"
                ])
            ]);
        }

        $buttons = [];
        if ($page > 1) {
            $buttons[] = Keyboard::inlineButton([
                'text' => '⬅️',
                'callback_data' => "{$callbackPrefix}:page:" . ($page - 1)
            ]);
        }
        if (count($items) === $perPage) {
            $buttons[] = Keyboard::inlineButton([
                'text' => '➡️',
                'callback_data' => "{$callbackPrefix}:page:" . ($page + 1)
            ]);
        }

        if ($buttons) {
            $keyboard->row($buttons);
        }

        if ($backCallback) {
            $keyboard->row([
                Keyboard::inlineButton([
                    'text' => 'Назад',
                    'callback_data' => $backCallback
                ])
            ]);
        }

        return $keyboard;
    }
}
