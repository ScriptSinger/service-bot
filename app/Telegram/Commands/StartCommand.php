<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use App\Services\Telegram\TelegramUserService;
use App\Telegram\Callbacks\TypeCallback;

class StartCommand extends Command
{
    protected string $name = 'start';
    protected string $description = 'Начать работу с ботом';

    public function handle()
    {
        $from = $this->update->getMessage()->from;

        TelegramUserService::syncUser($from);
        TelegramUserService::syncAvatar($from->id);

        $typeCallback = new TypeCallback();
        $typeCallback->renderTypesKeyboardForMessage($from->id);
    }
}
