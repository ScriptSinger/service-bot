<?php

namespace App\Telegram\Callbacks;

use App\Models\Manual;
use App\Services\YandexTemporaryUrlService;
use App\Telegram\Services\KeyboardService;
use App\Telegram\Services\MessageService;
use Telegram\Bot\Objects\CallbackQuery;

class ManualCallback
{
    public function __construct(
        protected KeyboardService $keyboard,
        protected MessageService $message
    ) {}

    public function handle(CallbackQuery $callback, array $data): void
    {
        $chatId    = $callback->message->chat->id;
        $messageId = $callback->message->message_id;

        $manualId = $data[1] ?? null;
        $manual   = Manual::with(['files', 'deviceModel'])->find($manualId);

        if (!$manual || $manual->files->isEmpty()) {
            $this->message->answerCallback($callback->id, 'Мануал или файлы не найдены');
            return;
        }

        $buttons = [];

        foreach ($manual->files as $file) {
            $label = $file->title ?? '📄 Скачать файл';

            if ($file->language) {
                $label .= " ({$file->language})";
            }

            $buttons[] = [
                'text' => $label,
                'url'  => YandexTemporaryUrlService::make($file->file_url),
            ];
        }

        if ($manual->deviceModel) {
            $buttons[] = [
                'text' => '⬅️ Назад',
                'callback_data' => "back_to_model:{$manual->deviceModel->brand_id}",
            ];
        }

        $keyboard = $this->keyboard->actions($buttons);

        $text = "📘 Мануал для модели: {$manual->deviceModel->name}\n\n"
            . "Выберите файл для скачивания:";

        $this->message->editMessage(
            $chatId,
            $messageId,
            $text,
            $keyboard
        );
    }
}
