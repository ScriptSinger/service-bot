<?php

namespace App\Telegram\Callbacks;

use App\Models\Brand;
use App\Models\DeviceModel;
use App\Models\TelegramUser;
use App\Telegram\Services\KeyboardService;
use App\Telegram\Services\MessageService;
use Telegram\Bot\Objects\CallbackQuery;

class ModelCallback
{
    public function __construct(
        protected KeyboardService $keyboard,
        protected MessageService $message
    ) {}

    /**
     * Обрабатывает нажатие на модель
     */
    public function handle(CallbackQuery $callback): void
    {
        $chatId    = $callback->message->chat->id;
        $messageId = $callback->message->message_id;
        $telegramId = $callback->from->id;

        // Получаем пользователя и его состояние
        $user = TelegramUser::where('telegram_id', $telegramId)->firstOrFail();

        $brandId = $user->state_data['brand_id'] ?? null;
        $typeId  = $user->state_data['device_type_id'] ?? null;

        if (!$brandId || !$typeId) {
            $this->message->answerCallback($callback->id, 'Ошибка: бренд или тип устройства не указан');
            return;
        }

        $brand = Brand::find($brandId);
        $deviceType = $brand ? $brand->deviceTypes()->find($typeId) : null;

        if (!$brand || !$deviceType) {
            $this->message->answerCallback($callback->id, 'Бренд или тип устройства не найден');
            return;
        }

        $this->showModels($chatId, $messageId, $brand, $deviceType, $user);
    }

    /**
     * Показываем модели бренда и типа
     */
    protected function showModels(int $chatId, int $messageId, Brand $brand, $deviceType, TelegramUser $user)
    {
        // Фильтр по бренду и типу
        $models = DeviceModel::where('brand_id', $brand->id)
            ->where('device_type_id', $deviceType->id)
            ->orderBy('name')
            ->get();

        $buttons = [];
        foreach ($models as $model) {
            $buttons[] = [
                'text' => $model->name,
                'callback_data' => (string)$model->id, // можно позже для следующего callback
            ];
        }

        // Кнопка «Назад» к брендам
        $buttons[] = [
            'text' => '⬅️ Назад',
            'callback_data' => 'back'
        ];

        $keyboard = $this->keyboard->buildKeyboard($buttons);

        // Переводим FSM в состояние выбора модели
        $user->update([
            'state' => 'waiting_model',
        ]);

        $this->message->editMessage(
            $chatId,
            $messageId,
            'Выберите модель:',
            $keyboard
        );
    }
}
