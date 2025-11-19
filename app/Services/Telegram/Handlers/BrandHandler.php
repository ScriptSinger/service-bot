<?php

namespace App\Services\Telegram\Handlers;

use App\Services\Domain\DeviceModelService;
use App\Services\Telegram\KeyboardBuilder;
use App\Services\Telegram\TelegramApi;

class BrandHandler
{
    protected DeviceModelService $modelService;
    protected KeyboardBuilder $kb;
    protected TelegramApi $api;

    public function __construct(DeviceModelService $modelService, KeyboardBuilder $kb, TelegramApi $api)
    {
        $this->modelService = $modelService;
        $this->kb = $kb;
        $this->api = $api;
    }

    public function handle(array $callbackQuery, $brandId): void
    {
        $chatId = $callbackQuery['message']['chat']['id'];

        $models = $this->modelService->getByBrand($brandId);

        $replyMarkup = $this->kb->deviceModels($models);

        $this->api->sendMessage($chatId, 'Выберите модель:', $replyMarkup);
    }
}
