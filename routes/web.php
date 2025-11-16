<?php

use App\Http\Controllers\TelegramController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/api/telegram/webhook', [TelegramController::class, 'webhook'])->withoutMiddleware([VerifyCsrfToken::class]);;
