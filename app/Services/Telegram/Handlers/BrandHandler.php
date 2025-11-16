<?php

namespace App\Services\Telegram\Handlers;

use App\Services\Domain\BrandService;
use App\Services\Telegram\TelegramApi;

class BrandHandler
{
    protected BrandService $brandService;
    protected TelegramApi $api;

    public function __construct(BrandService $brandService, TelegramApi $api)
    {
        $this->brandService = $brandService;
        $this->api = $api;
    }

    public function handle(array $callback, ?string $payload = null): void
    {
        // payload = brand id
        $chatId = $callback['message']['chat']['id'];

        $brand = $this->brandService->getById($payload);

        $text = "Бренд: {$brand->name}\nОписание: " . ($brand->description ?? '—');

        $this->api->sendMessage($chatId, $text);
    }
}
