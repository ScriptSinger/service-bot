<?php

namespace App\Services\Telegram\Handlers;

use App\Services\Domain\BrandService;
use App\Services\Telegram\KeyboardBuilder;
use App\Services\Telegram\TelegramApi;

class DeviceTypeHandler
{
    protected BrandService $brandService;
    protected KeyboardBuilder $kb;
    protected TelegramApi $api;

    public function __construct(BrandService $brandService, KeyboardBuilder $kb, TelegramApi $api)
    {
        $this->brandService = $brandService;
        $this->kb = $kb;
        $this->api = $api;
    }

    public function handle(array $callback, ?string $payload = null): void
    {
        // payload = slug устройства
        $chatId = $callback['message']['chat']['id'];

        $brands = $this->brandService->getByDeviceTypeSlug($payload);

        $replyMarkup = $this->kb->brands($brands);

        $this->api->sendMessage($chatId, 'Выберите бренд:', $replyMarkup);
    }
}
