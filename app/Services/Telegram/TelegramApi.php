<?php

namespace App\Services\Telegram;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

        Log::info('Sending message to chat ' . $chatId . ': ' . $text);

        if ($replyMarkup) {
            $payload['reply_markup'] = json_encode($replyMarkup);
        }

        Http::post($this->baseUrl . 'sendMessage', $payload);
    }
}
