<?php

namespace App\Telegram\Callbacks;

use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Keyboard\Keyboard;
use App\Models\Manual;
use Illuminate\Support\Facades\Storage;

class ManualCallback
{
    /**
     * Обработка callback для мануалов
     *
     * @param \Telegram\Bot\Objects\CallbackQuery $callback
     * @param array $data
     * @return void
     */
    public function handle($callback, array $data): void
    {
        $chatId = $callback->message->chat->id;
        $manualId = $data[1] ?? null;

        $manual = Manual::with('files')->find($manualId);

        if (!$manual || $manual->files->isEmpty()) {
            Telegram::answerCallbackQuery([
                'callback_query_id' => $callback->id,
                'text' => 'Мануал или файлы не найдены',
                'show_alert' => true,
            ]);
            return;
        }

        // Формируем клавиатуру с файлами
        $keyboard = Keyboard::make()->inline();
        foreach ($manual->files as $file) {
            $label = $file->title ?? '📄 Скачать файл';
            if (!empty($file->language)) {
                $label .= " ({$file->language})";
            }
            $keyboard->row([
                Keyboard::inlineButton([
                    'text' => $label,
                    'url' => Storage::disk('yandex')->url($file->file_url)
                ])
            ]);
        }

        // Кнопка «Назад» возвращает к модели
        if ($manual->deviceModel) {
            $keyboard->row([
                Keyboard::inlineButton([
                    'text' => '⬅️ Назад',
                    'callback_data' => "back_to_model:{$manual->deviceModel->id}"
                ])
            ]);
        }

        // Заголовок берём просто как "Мануал для модели" (title теперь в файлах)
        $text = "📘 Мануал для модели: {$manual->deviceModel->name}";

        Telegram::editMessageText([
            'chat_id' => $chatId,
            'message_id' => $callback->message->message_id,
            'text' => $text . "\n\nВыберите файл для скачивания:",
            'reply_markup' => $keyboard
        ]);
    }
}
