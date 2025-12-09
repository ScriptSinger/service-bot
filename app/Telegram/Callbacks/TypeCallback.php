<?php

namespace App\Telegram\Callbacks;

use App\Models\DeviceType;
use App\Models\TelegramUser;
use App\Telegram\Services\KeyboardService;
use App\Telegram\Services\MessageService;
use Telegram\Bot\Objects\CallbackQuery;

class TypeCallback
{
    public function __construct(
        protected KeyboardService $keyboardService,
        protected MessageService $messageService
    ) {}

    public function handle(CallbackQuery $callback)
    {
        $chatId = $callback->message->chat->id;
        $messageId = $callback->message->message_id;
        $telegramId = $callback->from->id;
        $value = $callback->data;

        $user = TelegramUser::where('telegram_id', $telegramId)->firstOrFail();

        // Здесь пока кнопка "Назад" на этом уровне не нужна
        if ($user->state !== 'waiting_type') return;

        $typeId = (int)$value;
        $deviceType = DeviceType::find($typeId);
        if (!$deviceType) {
            $this->messageService->answerCallback($callback->id, 'Тип устройства не найден');
            return;
        }

        // Сохраняем выбор и переводим FSM на бренд
        $user->update([
            'state' => 'waiting_brand',
            'state_data' => ['device_type_id' => $typeId],
        ]);

        $this->showBrands($chatId, $messageId, $deviceType);
    }

    // Отрисовать типы при возвращении из брендов
    public function showTypes(int $chatId, int $messageId)
    {
        $types = DeviceType::orderBy('name')->get();
        $buttons = $types->map(fn($type) => [
            'text' => $type->name,
            'callback_data' => (string)$type->id,
        ])->toArray();

        $keyboard = $this->keyboardService->buildKeyboard($buttons);
        $this->messageService->editMessage(
            $chatId,
            $messageId,
            'Выберите тип устройства:',
            $keyboard
        );
    }

    // Отрисовать бренды с кнопкой назад

    public function showBrands(int $chatId, int $messageId, DeviceType $deviceType)
    {
        $brands = $deviceType->brands()->orderBy('name')->get();
        $buttons = $brands->map(fn($brand) => [
            'text' => $brand->name,
            'callback_data' => (string)$brand->id,
        ])->toArray();

        $buttons[] = ['text' => '⬅️ Назад', 'callback_data' => 'back'];
        $keyboard = $this->keyboardService->buildKeyboard($buttons);
        $this->messageService->editMessage(
            $chatId,
            $messageId,
            'Выберите бренд:',
            $keyboard
        );
    }
}
