<?php

namespace App\Telegram\Callbacks;

use Telegram\Bot\Objects\CallbackQuery;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Models\DeviceModel;

class ModelCallback
{
    /**
     * Обработка callback для выбора модели устройства
     *
     * @param CallbackQuery $callback
     * @param array $data
     * @return void
     */
    public function handle(CallbackQuery $callback, array $data)
    {
        $chatId = $callback->message->chat->id;

        // Если это возврат к списку моделей
        if ($data[0] === 'back_to_model') {
            $brandId = $data[1] ?? null;
            $brand = \App\Models\Brand::find($brandId);

            if (!$brand) {
                Telegram::answerCallbackQuery([
                    'callback_query_id' => $callback->id,
                    'text' => 'Бренд не найден',
                    'show_alert' => true
                ]);
                return;
            }

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

            // Кнопка «Назад» возвращает к списку брендов
            $keyboard->row([
                Keyboard::inlineButton([
                    'text' => '⬅️ Назад',
                    'callback_data' => "back_to_brand:{$brand->id}"
                ])
            ]);

            Telegram::editMessageText([
                'chat_id' => $chatId,
                'message_id' => $callback->message->message_id,
                'text' => 'Выберите модель:',
                'reply_markup' => $keyboard
            ]);
            return;
        }

        // Обычный выбор модели
        $modelId = $data[1] ?? null;
        $model = DeviceModel::find($modelId);

        if (!$model) {
            Telegram::answerCallbackQuery([
                'callback_query_id' => $callback->id,
                'text' => 'Модель не найдена',
                'show_alert' => true
            ]);
            return;
        }

        // Формируем клавиатуру с опциями
        $keyboard = Keyboard::make()->inline();

        if ($model->manuals()->exists()) {
            $keyboard->row([
                Keyboard::inlineButton([
                    'text' => '📄 Мануалы',
                    'callback_data' => "manual:{$model->id}"
                ])
            ]);
        }

        if ($model->testModes()->exists()) {
            $keyboard->row([
                Keyboard::inlineButton([
                    'text' => '🛠 Тестовые режимы',
                    'callback_data' => "testmode:{$model->id}"
                ])
            ]);
        }

        if ($model->errorCodes()->exists()) {
            $keyboard->row([
                Keyboard::inlineButton([
                    'text' => '⚠ Ошибки',
                    'callback_data' => "error:{$model->id}"
                ])
            ]);
        }

        // Кнопка «Назад» возвращает к списку моделей бренда
        $keyboard->row([
            Keyboard::inlineButton([
                'text' => '⬅️ Назад',
                'callback_data' => "back_to_model:{$model->brand_id}"
            ])
        ]);

        Telegram::editMessageText([
            'chat_id' => $chatId,
            'message_id' => $callback->message->message_id,
            'text' => 'Выберите действие для модели:',
            'reply_markup' => $keyboard
        ]);
    }
}
