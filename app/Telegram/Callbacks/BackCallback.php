<?php

namespace App\Telegram\Callbacks;

use App\Models\Brand;
use App\Models\DeviceType;
use App\Models\TelegramUser;
use App\Telegram\Services\KeyboardService;
use App\Telegram\Services\MessageService;
use Telegram\Bot\Objects\CallbackQuery;

class BackCallback
{
    public function __construct(
        protected KeyboardService $keyboardService,
        protected MessageService $messageService
    ) {}

    /**
     * Обрабатывает кнопку "Назад"
     */
    public function handle(CallbackQuery $callback)
    {
        $telegramId = $callback->from->id;
        $user = TelegramUser::where('telegram_id', $telegramId)->firstOrFail();
        $chatId = $callback->message->chat->id;
        $messageId = $callback->message->message_id;

        switch ($user->state) {
            case 'waiting_brand':
                // Возврат к выбору типа устройства
                $user->update([
                    'state' => 'waiting_type',
                    'state_data' => null,
                ]);
                app(TypeCallback::class)->showTypes($chatId, $messageId);
                break;

            case 'waiting_model':
                // Возврат к выбору бренда
                $brandId = $user->state_data['brand_id'] ?? null;
                $typeId = $user->state_data['device_type_id'] ?? null;
                if ($brandId && $typeId) {
                    $brand = Brand::find($brandId);
                    $deviceType = DeviceType::find($typeId);
                    if ($brand && $deviceType) {
                        $user->update([
                            'state' => 'waiting_brand',
                            'state_data' => ['device_type_id' => $deviceType->id],
                        ]);
                        app(TypeCallback::class)->showBrands($chatId, $messageId, $deviceType);
                    }
                }
                break;

            default:
                // Если состояние неизвестное или на верхнем уровне, просто отвечаем
                $this->messageService->answerCallback($callback->id, 'Невозможно вернуться назад');
        }
    }
}
