<?php

namespace App\Telegram\Callbacks;

use Telegram\Bot\Objects\CallbackQuery;
use App\Models\Brand;
use App\Services\Telegram\NavigationService;

class BrandCallback
{
    public function handle(CallbackQuery $cb, array $data)
    {
        $nav = app(NavigationService::class);
        $ctx = $nav->decode($cb->data);

        // Фильтрация брендов через Eloquent ORM по типу устройства
        $brands = Brand::whereHas('deviceTypes', fn($q) => $q->where('device_types.id', $ctx['type']))
            ->orderBy('name')
            ->get();

        // Генерация клавиатуры через NavigationService
        $keyboard = $nav->buildBrandKeyboard($brands, $ctx);

        $nav->editMessage(
            $cb->message->chat->id,
            $cb->message->message_id,
            'Выберите бренд:',
            $keyboard
        );
    }
}
