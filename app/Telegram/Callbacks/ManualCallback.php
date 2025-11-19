<?php

namespace App\Telegram\Callbacks;

use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Keyboard\Keyboard;
use App\Models\Manual;

class ManualCallback
{
    public static function handle($callback, $data)
    {
        $chatId = $callback->getMessage()->getChat()->id;
        $manualId = $data[1];

        $manual = Manual::with('files')->find($manualId);

        if (!$manual) {
            return Telegram::answerCallbackQuery([
                'callback_query_id' => $callback->id,
                'text' => 'Manual not found',
                'show_alert' => true,
            ]);
        }

        // Клавиатура
        $keyboard = Keyboard::make()->inline();

        foreach ($manual->files as $file) {
            $keyboard->row([
                Keyboard::inlineButton([
                    'text' => '📄 Скачать файл',
                    // 'url'  => url($file->file_url),   // ← ПРАВИЛЬНО
                    'url' => asset('storage/' . $file->file_url)

                ]),
            ]);
        }

        return Telegram::editMessageText([
            'chat_id' => $chatId,
            'message_id' => $callback->getMessage()->message_id, // ← ПРАВИЛЬНО
            'text' => "📘 Мануал: {$manual->title}\n\nВыберите файл для скачивания:",
            'reply_markup' => $keyboard
        ]);
    }
}
