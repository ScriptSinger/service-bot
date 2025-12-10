<?php

use App\BotMan\Conversations\DeviceSelectionConversation;
use App\Http\Controllers\BotManController;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Telegram\TelegramDriver;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| BotMan webhook
|--------------------------------------------------------------------------
*/



Route::post('/botman', [BotManController::class, 'handle']);
