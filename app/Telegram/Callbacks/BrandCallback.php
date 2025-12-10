<?php

namespace App\Telegram\Callbacks;

use App\Models\Brand;
use App\Models\TelegramUser;
use App\Telegram\Services\KeyboardService;
use App\Telegram\Services\MessageService;

use Telegram\Bot\Objects\CallbackQuery;

class BrandCallback
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
        $data = $callback->data;
        $user = TelegramUser::where('telegram_id', $telegramId)->firstOrFail();

        $typeId = $user->state_data['device_type_id'] ?? null;

        // Выбор бренда
        $brandId = (int)$data;
        $brand = Brand::find($brandId);


        // FSM → модели
        $user->update([
            'state' => 'waiting_model',
            'state_data' => [
                'device_type_id' => $user->state_data['device_type_id'] ?? null,
                'brand_id' => $brand->id,
            ],
        ]);

        $this->showModels($chatId, $messageId, $brand, $typeId);
    }

    protected function showModels(int $chatId, int $messageId, Brand $brand, $typeId)
    {
        $models = $brand->deviceModels()
            ->where('device_type_id', [$typeId])
            ->orderBy('name')
            ->get();

        $buttons = $models->map(fn($model) => [
            'text' => $model->name,
            'callback_data' => (string)$model->id,
        ])->toArray();

        // Кнопка «Назад» к брендам
        $buttons[] = ['text' => '⬅️ Назад', 'callback_data' => 'back'];

        $keyboard = $this->keyboardService->buildKeyboard($buttons);

        $this->messageService->editMessage(
            $chatId,
            $messageId,
            'Выберите модель:',
            $keyboard
        );
    }
}
