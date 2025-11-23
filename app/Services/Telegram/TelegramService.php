<?php

namespace App\Services\Telegram;

use Telegram\Bot\Objects\CallbackQuery;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramService
{
    public function answerCallback(CallbackQuery $callback, string $text, bool $alert = false)
    {
        return Telegram::answerCallbackQuery([
            'callback_query_id' => $callback->id,
            'text' => $text,
            'show_alert' => $alert,
        ]);
    }

    public function editMessage(CallbackQuery $callback, string $text, ?Keyboard $keyboard = null)
    {
        return Telegram::editMessageText([
            'chat_id' => $callback->message->chat->id,
            'message_id' => $callback->message->message_id,
            'text' => $text,
            'reply_markup' => $keyboard
        ]);
    }
}
