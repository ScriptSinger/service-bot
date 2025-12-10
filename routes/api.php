<?php

use App\Models\TelegramUser;
use App\Telegram\Callbacks\BrandCallback;
use App\Telegram\Callbacks\TypeCallback;
use App\Telegram\Callbacks\BackCallback;
use App\Telegram\Callbacks\ManualCallback;
use App\Telegram\Callbacks\ModelCallback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

Route::post('/telegram/webhook', function (Request $request) {

    $update = Telegram::commandsHandler(true);

    if ($update && $update->callbackQuery) {
        $callback = $update->callbackQuery;
        $telegramId = $callback->from->id;

        $user = TelegramUser::where('telegram_id', $telegramId)->firstOrFail();
        $data = $callback->data;

        // 1️⃣ Сначала проверяем кнопку "Назад"
        if ($data === 'back') {
            app(BackCallback::class)->handle($callback);
            return;
        }

        // 2️⃣ FSM: определяем обработчик по состоянию
        switch ($user->state) {
            case 'waiting_type':
                app(TypeCallback::class)->handle($callback);
                break;

            case 'waiting_brand':
                app(BrandCallback::class)->handle($callback);
                break;

            case 'waiting_model':
                app(ModelCallback::class)->handle($callback);
                break;

            case 'waiting_manual':
                app(ManualCallback::class)->handle($callback);
                break;
        }
    }
});
