<?php

namespace App\Telegram\Callbacks;

use Telegram\Bot\Objects\CallbackQuery;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Models\Brand;
use App\Models\DeviceType;
use Illuminate\Support\Facades\Log;

class BrandCallback
{
    public function handle(CallbackQuery $callback, array $data)
    {
        $chatId = $callback->message->chat->id;
        $parts = explode(':', $callback->data);
        $prefix = $parts[0] ?? null;
        $brandId = $parts[1] ?? null;
        $typeId  = $parts[2] ?? null;

        // Если это возврат к списку брендов
        if ($data[0] === 'back_to_brand') {
            $brands = Brand::orderBy('name')->get(); // ВСЕ бренды

            $keyboard = Keyboard::make()->inline();
            foreach ($brands as $brand) {
                $keyboard->row([
                    Keyboard::inlineButton([
                        'text' => $brand->name,
                        'callback_data' => "brand:{$brand->id}"
                    ])
                ]);
            }

            // Кнопка «Назад» всегда возвращает к списку типов устройств
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
            return;
        }

        // Обычный выбор бренда
        $brandId = $data[1] ?? null;
        $brand = Brand::find($brandId);

        if (!$brand) {
            Telegram::answerCallbackQuery([
                'callback_query_id' => $callback->id,
                'text' => 'Бренд не найден',
                'show_alert' => true
            ]);
            return;
        }

        // Получаем модели бренда
        $models = $brand->deviceModels()
            ->when($typeId, fn($q) => $q->where('device_type_id', $typeId))
            ->orderBy('name')
            ->get();


        $keyboard = Keyboard::make()->inline();
        foreach ($models as $model) {
            $keyboard->row([
                Keyboard::inlineButton([
                    'text' => $model->name,
                    'callback_data' => "model:{$model->id}"
                ])
            ]);
        }

        // Кнопка «Назад» всегда возвращает к списку брендов
        $keyboard->row([
            Keyboard::inlineButton([
                'text' => '⬅️ Назад',
                'callback_data' => 'back_to_brand' // без ID типа
            ])
        ]);

        Telegram::editMessageText([
            'chat_id' => $chatId,
            'message_id' => $callback->message->message_id,
            'text' => 'Выберите модель:',
            'reply_markup' => $keyboard
        ]);
    }
}
