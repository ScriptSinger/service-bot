<?php

namespace App\Providers;

use App\Models\BroadcastMessage;
use App\Observers\BroadcastMessageObserver;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        BroadcastMessage::observe(BroadcastMessageObserver::class);
    }
}
