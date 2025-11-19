<?php

namespace App\Services\Telegram;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class KeyboardBuilder
{
    /**
     * Кнопки для выбора типов устройств
     */
    public function deviceTypes($types): array
    {
        // Если передана Collection, преобразуем в массив
        if ($types instanceof \Illuminate\Support\Collection) {
            $types = $types->toArray();
        }

        $buttons = array_map(fn($t) => [
            'text' => $t['name'],
            'callback_data' => "device_type:{$t['id']}"
        ], $types);

        return ['inline_keyboard' => array_chunk($buttons, 2)];
    }

    /**
     * Кнопки для выбора брендов
     */
    public function brands(Collection|array $brands): array
    {
        $buttons = collect($brands)
            ->map(fn($b) => [
                'text' => $b['name'],
                'callback_data' => "brand_show:{$b['id']}"
            ])
            ->chunk(2)
            ->map(fn($chunk) => array_values($chunk->toArray())) // <- вот эта строчка
            ->values() // <- чтобы ключи массива стали 0,1,2...
            ->toArray();

        $keyboard = ['inline_keyboard' => $buttons];

        Log::info('KeyboardBuilder output:', ['keyboard' => $keyboard]);

        return $keyboard;
    }

    public function deviceModels($models): array
    {
        if ($models instanceof \Illuminate\Support\Collection) {
            $models = $models->toArray();
        }

        $buttons = array_map(fn($m) => [
            'text' => $m['name'],
            'callback_data' => "manuals_for_model:{$m['id']}" // новый callback для мануалов
        ], $models);

        return ['inline_keyboard' => array_chunk($buttons, 2)];
    }
}
