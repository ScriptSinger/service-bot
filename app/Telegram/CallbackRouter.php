<?php

namespace App\Telegram;

use App\Services\Telegram\NavigationService;

use App\Telegram\Callbacks\{
    TypeCallback,
    BrandCallback,
    ModelCallback,
    ManualCallback,
};
use Telegram\Bot\Laravel\Facades\Telegram;

class CallbackRouter
{
    public static function handle($callback)
    {
        $nav = app(NavigationService::class);
        $ctx = $nav->decode($callback->data); // ['step' => 'brands', 'type' => 1]

        $step = $ctx['step'] ?? null;

        $handlerClass = match ($step) {
            'types' => TypeCallback::class,
            'brands' => BrandCallback::class,
            'models' => ModelCallback::class,
            'manuals' => ManualCallback::class,
            default => null
        };

        if ($handlerClass) {
            $handler = app($handlerClass);
            return $handler->handle($callback, $ctx);
        }

        // неизвестная команда
        Telegram::answerCallbackQuery([
            'callback_query_id' => $callback->id,
            'text' => 'Неизвестная команда',
            'show_alert' => true,
        ]);
    }
}
