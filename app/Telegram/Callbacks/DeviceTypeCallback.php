<?php

namespace App\Telegram\Callbacks;

use Telegram\Bot\Objects\CallbackQuery;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Models\DeviceType;
use App\Models\Brand;

class DeviceTypeCallback
{
    /**
     * Обработка callback для выбора типа устройства
     *
     * @param CallbackQuery $callback
     * @param array $data
     * @return void
     */
    public static function handle(CallbackQuery $callback, array $data)
    {
        $chatId = $callback->message->chat->id;

        // Извлекаем выбранный тип устройства
        $typeId = $data[1] ?? null;
        $deviceType = DeviceType::find($typeId);

        if (!$deviceType) {
            Telegram::answerCallbackQuery([
                'callback_query_id' => $callback->id,
                'text' => 'Тип устройства не найден',
                'show_alert' => true
            ]);
            return;
        }

        // Получаем бренды для выбранного типа устройства
        $brands = $deviceType->brands()->orderBy('name')->get();

        // Формируем клавиатуру
        $keyboard = Keyboard::make()->inline();

        foreach ($brands as $brand) {
            $keyboard->row([
                Keyboard::inlineButton([
                    'text' => $brand->name,
                    'callback_data' => "brand:{$brand->id}"
                ])
            ]);
        }

        // Добавляем кнопку "Назад"
        $keyboard->row([
            Keyboard::inlineButton([
                'text' => 'Назад',
                'callback_data' => 'back'
            ])
        ]);

        // Редактируем сообщение с новой клавиатурой
        Telegram::editMessageText([
            'chat_id' => $chatId,
            'message_id' => $callback->message->message_id,
            'text' => 'Выберите бренд:',
            'reply_markup' => $keyboard
        ]);
    }
}
