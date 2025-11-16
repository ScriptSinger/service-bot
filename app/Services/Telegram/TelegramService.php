<?php

namespace App\Services\Telegram;

use App\Services\Telegram\Handlers\StartHandler;
use App\Services\Telegram\Handlers\DeviceTypeHandler;
use App\Services\Telegram\Handlers\BrandHandler;

class TelegramService
{
    protected array $handlers = [];

    public function __construct()
    {
        // mapping команд/префиксов -> обработчики
        $this->handlers = [
            'start' => StartHandler::class,
            'device_type' => DeviceTypeHandler::class,
            'brand_show' => BrandHandler::class,
        ];
    }

    public function handleUpdate(array $update): void
    {
        // Обработка message (команды) и callback_query

        if (isset($update['message'])) {
            $text = $update['message']['text'] ?? '';
            $chatId = $update['message']['chat']['id'] ?? null;

            if ($text === '/start') {
                $handler = app(StartHandler::class);
                $handler->handle($update);
                return;
            }

            // тут можно добавить обработку текстовых команд
        }

        if (isset($update['callback_query'])) {
            $cb = $update['callback_query'];
            $data = $cb['data'] ?? '';

            // Ожидаем формат: command:payload или command
            [$command, $payload] = $this->parseCallbackData($data);

            if (isset($this->handlers[$command])) {
                $class = $this->handlers[$command];
                $handler = app($class);
                $handler->handle($cb, $payload);
            }
        }
    }

    protected function parseCallbackData(string $data): array
    {
        if (str_contains($data, ':')) {
            [$command, $payload] = explode(':', $data, 2);
            return [$command, $payload];
        }

        return [$data, null];
    }
}
