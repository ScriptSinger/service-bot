<?php

namespace App\Providers;

use App\Models\BroadcastMessage;
use App\Observers\BroadcastMessageObserver;
use App\Services\Telegram\TelegramApiFactory;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (config('telegram.proxy')) {
            config(['telegram.http_client_handler' => TelegramApiFactory::makeHttpClientHandler()]);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}
}
