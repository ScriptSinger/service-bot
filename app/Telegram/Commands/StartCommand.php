<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use App\Models\DeviceType;
use App\Services\Telegram\TelegramUserService;
use App\Telegram\Services\KeyboardService;

class StartCommand extends Command
{
    protected string $name = 'start';
    protected string $description = 'Начать работу с ботом';

    public function handle()
    {
        $from = $this->update->getMessage()->from;

        // 1. Создаём / обновляем пользователя
        $user = TelegramUserService::syncUser($from);
        TelegramUserService::syncAvatar($from->id);

        // 2. ИНИЦИАЛИЗИРУЕМ FSM
        $user->update([
            'state' => 'waiting_type',
            'state_data' => null,
        ]);

        // 3. Получаем типы устройств
        $types = DeviceType::orderBy('name')->get();

        // 4. Формируем кнопки
        $buttons = $types->map(fn($type) => [
            'text' => $type->name,
            'callback_data' => (string) $type->id,
        ])->toArray();

        // 5. Клавиатура
        $keyboard = app(KeyboardService::class)->buildKeyboard($buttons);

        // 6. Ответ пользователю
        $this->replyWithMessage([
            'text' => 'Выберите тип устройства:',
            'reply_markup' => $keyboard,
        ]);
    }
}
