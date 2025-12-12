<?php

namespace App\Telegram\Callbacks;

use Telegram\Bot\Objects\CallbackQuery;
use App\Models\Manual;
use App\Services\Telegram\NavigationService;
use Illuminate\Support\Facades\Log;

class ManualCallback
{
    public function handle(CallbackQuery $callback): void
    {
        $nav = app(NavigationService::class);

        // Декодируем контекст из callback_data
        $ctx = $nav->decode($callback->data);
        $modelId = $ctx['model'] ?? null;
        $manuals = Manual::where('device_model_id', $modelId)->get();

        $keyboard = $nav->buildManualKeyboard($manuals, $ctx);
        $text = "📘 Мануал для модели";

        $nav->editMessage(
            $callback->message->chat->id,
            $callback->message->message_id,
            $text,
            $keyboard
        );
    }
}
