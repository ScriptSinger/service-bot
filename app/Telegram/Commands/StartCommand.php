<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;
use App\Models\DeviceType;
use App\Services\Telegram\TelegramUserService;
use App\Telegram\Helpers;

class StartCommand extends Command
{
    protected string $name = 'start';
    protected string $description = 'Начать работу с ботом';

    public function handle()
    {
        $from = $this->update->getMessage()->from;

        TelegramUserService::syncUser($from);
        TelegramUserService::syncAvatar($from->id);



        $types = DeviceType::orderBy('name')->get();

        $keyboard = Keyboard::make()->inline();

        foreach ($types as $type) {
            $keyboard->row([
                Keyboard::inlineButton([
                    'text' => $type->name,
                    'callback_data' => "type:{$type->id}"
                ])
            ]);
        }

        $this->replyWithMessage([
            'text' => "Выберите тип устройства:",
            'reply_markup' => $keyboard
        ]);
    }
}
