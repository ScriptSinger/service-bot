<?php

use App\Telegram\CallbackRouter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

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

    $update = Telegram::commandsHandler(true);

    if ($update && $update->callbackQuery) {
        CallbackRouter::handle($update->callbackQuery);
    }
});
