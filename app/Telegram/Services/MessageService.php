<?php

namespace App\Telegram\Services;

use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Keyboard\Keyboard;

class MessageService
{
    public function editMessage(int $chatId, int $messageId, string $text, Keyboard $keyboard)
    {
        Telegram::editMessageText([
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'text' => $text,
            'reply_markup' => $keyboard
        ]);
    }

    public function answerCallback(string $callbackId, string $text)
    {
        Telegram::answerCallbackQuery([
            'callback_query_id' => $callbackId,
            'text' => $text,
            'show_alert' => true
        ]);
    }
}
