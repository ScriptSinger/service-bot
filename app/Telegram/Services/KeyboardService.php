<?php

namespace App\Telegram\Services;

use Telegram\Bot\Keyboard\Keyboard;

class KeyboardService
{
    /**
     * Строит inline клавиатуру из массива кнопок
     * $items = [
     *   ['text' => 'Название', 'callback_data' => 'brand:5:type:3'],
     *   ...
     * ]
     * $extraButtons = дополнительные кнопки [['text'=>'⬅️ Назад','callback_data'=>'back']]
     */
    public function buildKeyboard(array $items, array $extraButtons = []): Keyboard
    {
        $keyboard = Keyboard::make()->inline();

        // Основные кнопки
        foreach ($items as $item) {
            $keyboard->row([
                Keyboard::inlineButton([
                    'text' => $item['text'],
                    'callback_data' => $item['callback_data']
                ])
            ]);
        }

        // Дополнительные кнопки, например «Назад»
        foreach ($extraButtons as $button) {
            $keyboard->row([
                Keyboard::inlineButton([
                    'text' => $button['text'],
                    'callback_data' => $button['callback_data']
                ])
            ]);
        }

        return $keyboard;
    }
}
