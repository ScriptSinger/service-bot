<?php

namespace App\Telegram\Callbacks;

use Telegram\Bot\Objects\CallbackQuery;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Models\DeviceType;
use App\Services\Telegram\NavigationService;

class TypeCallback
{
    public function handle(CallbackQuery $cb, array $data)
    {
        $nav = app(NavigationService::class);
        $ctx = $nav->decode($cb->data);

        $types = DeviceType::orderBy('name')->get();
        $keyboard = $nav->buildTypeKeyboard($types);

        // Редактируем сообщение, так как это callback
        $nav->editMessage(
            $cb->message->chat->id,
            $cb->message->message_id,
            'Выберите тип устройства:',
            $keyboard
        );
    }

    /**
     * Метод для рендера клавиатуры в команде /start
     */
    public function renderTypesKeyboardForMessage($chatId)
    {
        $nav = app(NavigationService::class);
        $types = DeviceType::orderBy('name')->get();
        $keyboard = $nav->buildTypeKeyboard($types);

        // Отправляем новое сообщение
        $nav->sendMessage($chatId, 'Выберите тип устройства:', $keyboard);
    }
}
