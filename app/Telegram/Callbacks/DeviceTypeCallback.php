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
    public function handle(CallbackQuery $callback, array $data)
    {
        $chatId = $callback->message->chat->id;

        // Извлекаем выбранный тип устройства, если есть
        $typeId = $data[1] ?? null;

        // Если это возврат по кнопке «Назад», просто показываем все DeviceType
        if ($data[0] === 'back_to_type' || !$typeId) {
            $deviceTypes = DeviceType::orderBy('name')->get();

            $keyboard = Keyboard::make()->inline();
            foreach ($deviceTypes as $type) {
                $keyboard->row([
                    Keyboard::inlineButton([
                        'text' => $type->name,
                        'callback_data' => "type:{$type->id}"
                    ])
                ]);
            }

            Telegram::editMessageText([
                'chat_id' => $chatId,
                'message_id' => $callback->message->message_id,
                'text' => 'Выберите тип устройства:',
                'reply_markup' => $keyboard
            ]);
            return;
        }

        // Ищем выбранный DeviceType
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

        $keyboard = Keyboard::make()->inline();
        foreach ($brands as $brand) {
            $keyboard->row([
                Keyboard::inlineButton([
                    'text' => $brand->name,
                    'callback_data' => "brand:{$brand->id}"
                ])
            ]);
        }

        // Кнопка «Назад» возвращает на список типов
        $keyboard->row([
            Keyboard::inlineButton([
                'text' => '⬅️ Назад',
                'callback_data' => 'back_to_type'
            ])
        ]);

        Telegram::editMessageText([
            'chat_id' => $chatId,
            'message_id' => $callback->message->message_id,
            'text' => 'Выберите бренд:',
            'reply_markup' => $keyboard
        ]);
    }
}
