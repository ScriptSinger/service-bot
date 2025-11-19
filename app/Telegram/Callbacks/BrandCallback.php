<?php

namespace App\Telegram\Callbacks;

use Telegram\Bot\Objects\CallbackQuery;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Models\Brand;
use App\Models\DeviceModel;

class BrandCallback
{
    /**
     * Обработка callback для выбора бренда
     *
     * @param CallbackQuery $callback
     * @param array $data
     * @return void
     */
    public static function handle(CallbackQuery $callback, array $data)
    {
        $chatId = $callback->message->chat->id;

        // Извлекаем выбранный бренд
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

        // Получаем модели для выбранного бренда
        $models = $brand->deviceModels()->orderBy('name')->get();

        // Формируем клавиатуру
        $keyboard = Keyboard::make()->inline();

        foreach ($models as $model) {
            $keyboard->row([
                Keyboard::inlineButton([
                    'text' => $model->name,
                    'callback_data' => "model:{$model->id}"
                ])
            ]);
        }

        // Добавляем кнопку "Назад" к типам устройств
        $keyboard->row([
            Keyboard::inlineButton([
                'text' => 'Назад',
                'callback_data' => "back_to_types"
            ])
        ]);

        // Редактируем сообщение с новой клавиатурой
        Telegram::editMessageText([
            'chat_id' => $chatId,
            'message_id' => $callback->message->message_id,
            'text' => 'Выберите модель:',
            'reply_markup' => $keyboard
        ]);
    }
}
