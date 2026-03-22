<?php

use App\Http\Middleware\TelegramUserSync;
use App\Jobs\ProcessTelegramUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



// Route::prefix('v1')->group(function () {
//     Route::get('/device-types', [DeviceTypeController::class, 'index']);
//     Route::get('/brands/{id}/models', [DeviceModelController::class, 'index']);
//     Route::get('/models/{deviceModel}', [DeviceModelController::class, 'show']);
//     Route::get('/models/{id}/manuals', [ManualController::class, 'index']);
//     Route::get('/models/{id}/error-codes', [ErrorCodeController::class, 'index']);
// });

Route::post('/telegram/webhook', function (Request $request) {
    $payload = $request->all();
    $updateId = $payload['update_id'] ?? null;
    $type = array_key_first(array_intersect_key($payload, array_flip([
        'message',
        'edited_message',
        'channel_post',
        'edited_channel_post',
        'inline_query',
        'chosen_inline_result',
        'callback_query',
        'shipping_query',
        'pre_checkout_query',
        'poll',
        'poll_answer',
        'my_chat_member',
        'chat_member',
        'chat_join_request',
    ]))) ?? 'unknown';

    \Log::info('telegram.webhook', ['update_id' => $updateId, 'type' => $type]);

    ProcessTelegramUpdate::dispatch($payload)->onQueue('telegram.webhook');
    return response()->json(['ok' => true]);
})->middleware(TelegramUserSync::class);
