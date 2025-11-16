<?php

namespace App\Services\Telegram;

class KeyboardBuilder
{
    public function deviceTypes($deviceTypes): array
    {
        $rows = [];
        foreach ($deviceTypes as $dt) {
            $rows[] = [['text' => $dt->name, 'callback_data' => 'device_type:' . $dt->slug]];
        }

        return ['inline_keyboard' => $rows];
    }

    public function brands($brands): array
    {
        $rows = [];
        foreach ($brands as $b) {
            $rows[] = [['text' => $b->name, 'callback_data' => 'brand_show:' . $b->id]];
        }

        return ['inline_keyboard' => $rows];
    }
}
