<?php

namespace App\Services\Telegram\Handlers;

use App\Services\Domain\DeviceTypeService;
use App\Services\Telegram\KeyboardBuilder;
use App\Services\Telegram\TelegramApi;

class StartHandler
{
    protected DeviceTypeService $deviceTypeService;
    protected KeyboardBuilder $kb;
    protected TelegramApi $api;

    public function __construct(DeviceTypeService $deviceTypeService, KeyboardBuilder $kb, TelegramApi $api)
    {
        $this->deviceTypeService = $deviceTypeService;
        $this->kb = $kb;
        $this->api = $api;
    }

    public function handle(array $update): void
    {
        $chatId = $update['message']['chat']['id'];

        $deviceTypes = $this->deviceTypeService->getAll();

        $replyMarkup = $this->kb->deviceTypes($deviceTypes);

        $this->api->sendMessage($chatId, 'Выберите тип устройства:', $replyMarkup);
    }
}
