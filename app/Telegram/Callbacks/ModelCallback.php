<?php

namespace App\Telegram\Callbacks;

use Telegram\Bot\Objects\CallbackQuery;
use App\Models\DeviceModel;
use App\Models\Brand;
use App\Services\Telegram\NavigationService;

class ModelCallback
{
    public function handle(CallbackQuery $cb, array $data)
    {
        $nav = app(NavigationService::class);

        // Декодируем контекст из callback_data
        $ctx = $nav->decode($cb->data);

        $brandId = $ctx['brand'] ?? null;
        $typeId  = $ctx['type'] ?? null;

        // Фильтруем модели по бренду и типу
        $models = DeviceModel::query()
            ->when($brandId, fn($q) => $q->where('brand_id', $brandId))
            ->when($typeId, fn($q) => $q->where('device_type_id', $typeId))
            ->orderBy('name')
            ->get();

        // Формируем клавиатуру
        $keyboard = $nav->buildModelKeyboard($models, $ctx);

        // Редактируем текущее сообщение
        $nav->editMessage(
            $cb->message->chat->id,
            $cb->message->message_id,
            'Выберите модель:',
            $keyboard
        );
    }
}
