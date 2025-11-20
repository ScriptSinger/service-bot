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
        $data = explode(':', $callback->data);
        $command = $data[0];


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

            return Telegram::answerCallbackQuery([
                'callback_query_id' => $callback->id,
                'text' => 'Unknown command'
            ]);
        }

        return $handler::handle($callback, $data);
    }
}
