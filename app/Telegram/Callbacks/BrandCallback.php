<?php

namespace App\Telegram\Callbacks;

use Telegram\Bot\Objects\CallbackQuery;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Models\Brand;
use App\Models\DeviceType;

class BrandCallback
{
    /**
     * Обработка callback для выбора бренда
     *
     * @param CallbackQuery $callback
     * @param array $data
     * @return void
     */
    public function handle(CallbackQuery $callback, array $data)
    {
        $chatId = $callback->message->chat->id;

        // Если это возврат на список брендов (кнопка «Назад»)
        if ($data[0] === 'back_to_brand') {
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

            // Кнопка «Назад» возвращает к списку типов
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
        $models = $brand->deviceModels()->orderBy('name')->get();

        $keyboard = Keyboard::make()->inline();
        foreach ($models as $model) {
            $keyboard->row([
                Keyboard::inlineButton([
                    'text' => $model->name,
                    'callback_data' => "model:{$model->id}"
                ])
            ]);
        }

        // Кнопка «Назад» возвращает к списку брендов (с выбранным типом)
        $keyboard->row([
            Keyboard::inlineButton([
                'text' => '⬅️ Назад',
                'callback_data' => "back_to_brand:{$brand->deviceTypes->first()->id}"
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
