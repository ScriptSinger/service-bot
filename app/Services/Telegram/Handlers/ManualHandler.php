<?php

namespace App\Services\Telegram\Handlers;

use App\Models\DeviceModel;
use App\Services\Telegram\TelegramApi;
use App\Services\Telegram\KeyboardBuilder;

class ManualHandler
{
    protected TelegramApi $api;
    protected KeyboardBuilder $kb;

    public function __construct(TelegramApi $api, KeyboardBuilder $kb)
    {
        $this->api = $api;
        $this->kb = $kb;
    }

    public function handle(array $callback, ?string $payload = null)
    {
        $chatId = $callback['message']['chat']['id'];

        $deviceModel = DeviceModel::findOrFail($payload);
        $manuals = $deviceModel->manuals;

        if ($manuals->isEmpty()) {
            $this->api->sendMessage($chatId, "Для этой модели мануалы не найдены.");
            return;
        }

        // Формируем кнопки для мануалов
        $buttons = collect($manuals)
            ->map(fn($m) => [
                'text' => $m->title,
                'url' => $m->file_url // тут Telegram откроет ссылку на файл
            ])
            ->chunk(2)
            ->map(fn($chunk) => array_values($chunk->toArray()))
            ->values()
            ->toArray();

        $replyMarkup = ['inline_keyboard' => $buttons];

        $this->api->sendMessage($chatId, "Выберите мануал для скачивания:", $replyMarkup);
    }
}
