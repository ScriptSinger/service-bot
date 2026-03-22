<?php

namespace App\Services\Telegram;

use Telegram\Bot\Objects\CallbackQuery;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Exceptions\TelegramResponseException;
use Throwable;

class TelegramService
{
    public function answerCallback(CallbackQuery $callback, string $text, bool $alert = false)
    {
        try {
            return Telegram::answerCallbackQuery([
                'callback_query_id' => $callback->id,
                'text' => $text,
                'show_alert' => $alert,
            ]);
        } catch (TelegramResponseException $e) {
            \Log::warning('telegram.answerCallback.failed', [
                'callback_query_id' => $callback->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        } catch (Throwable $e) {
            \Log::error('telegram.answerCallback.error', [
                'callback_query_id' => $callback->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function editMessage(CallbackQuery $callback, string $text, ?Keyboard $keyboard = null)
    {
        try {
            return Telegram::editMessageText([
                'chat_id' => $callback->message->chat->id,
                'message_id' => $callback->message->message_id,
                'text' => $text,
                'reply_markup' => $keyboard
            ]);
        } catch (TelegramResponseException $e) {
            \Log::warning('telegram.editMessage.failed', [
                'chat_id' => $callback->message->chat->id,
                'message_id' => $callback->message->message_id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        } catch (Throwable $e) {
            \Log::error('telegram.editMessage.error', [
                'chat_id' => $callback->message->chat->id,
                'message_id' => $callback->message->message_id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
