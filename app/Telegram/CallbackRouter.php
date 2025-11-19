<?php

namespace App\Telegram;

use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\CallbackQuery;
use App\Telegram\Callbacks\{
    DeviceTypeCallback,
    BrandCallback,
    ModelCallback,
    ManualCallback,
    ErrorCallback,
    TestModeCallback
};
use Illuminate\Support\Facades\Log;

class CallbackRouter
{
    public static function handle(CallbackQuery $callback)
    {
        Log::info('Callback received', [
            'data' => $callback->data,
        ]);

        $data = explode(':', $callback->data);
        $command = $data[0];

        Log::info('Parsed callback', [
            'command' => $command,
            'parts' => $data,
        ]);

        $handler = match ($command) {
            'type' => DeviceTypeCallback::class,
            'brand' => BrandCallback::class,
            'model' => ModelCallback::class,
            'manual' => ManualCallback::class,
            'manual_file' => ManualCallback::class,
            'manual_file_list' => ManualCallback::class,
            'back_to_model' => ModelCallback::class,
            default => null,
        };

        if (!$handler) {
            Log::warning('Unknown callback command', [
                'command' => $command,
            ]);

            return Telegram::answerCallbackQuery([
                'callback_query_id' => $callback->id,
                'text' => 'Unknown command'
            ]);
        }

        Log::info('Handler found', [
            'handler' => $handler,
        ]);

        return $handler::handle($callback, $data);
    }
}
