<?php

namespace App\Services\Telegram;

use Illuminate\Support\Facades\Http;

class TelegramApi
{
    protected string $baseUrl;

    public function __construct()
    {
        $token = config('services.telegram.token') ?? env('TELEGRAM_BOT_TOKEN');
        $this->baseUrl = "https://api.telegram.org/bot{$token}/";
    }

    public function sendMessage($chatId, string $text, array $replyMarkup = null)
    {
        $payload = [
            'chat_id' => $chatId,
            'text' => $text,
        ];

        if ($replyMarkup) {
            $payload['reply_markup'] = $replyMarkup;
        }

        Http::post($this->baseUrl . 'sendMessage', $payload);
    }
}
