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
    public static function handle(CallbackQuery $callback, array $data)
    {
        $chatId = $callback->message->chat->id;

        // Извлекаем выбранную модель
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

        // Кнопка "Назад" к брендам
        $keyboard->row([
            Keyboard::inlineButton([
                'text' => 'Назад',
                'callback_data' => "back_to_brands:{$model->brand_id}"
            ])
        ]);

        // Редактируем сообщение с новой клавиатурой
        Telegram::editMessageText([
            'chat_id' => $chatId,
            'message_id' => $callback->message->message_id,
            'text' => 'Выберите действие для модели:',
            'reply_markup' => $keyboard
        ]);
    }
}
