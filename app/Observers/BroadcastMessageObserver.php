<?php

namespace App\Observers;

use App\Jobs\SendBroadcastJob;
use App\Models\BroadcastMessage;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Support\Facades\Log;

class BroadcastMessageObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the BroadcastMessage "created" event.
     */
    public function created(BroadcastMessage $broadcastMessage): void
    {
        $userIds = $broadcastMessage->telegramUsers()->pluck('telegram_users.id')->toArray();


        $broadcastMessage->telegramUsers()->sync($userIds);

        SendBroadcastJob::dispatch($broadcastMessage, $userIds);
    }

    /**
     * Handle the BroadcastMessage "updated" event.
     */
    public function updated(BroadcastMessage $broadcastMessage): void {}

    /**
     * Handle the BroadcastMessage "deleted" event.
     */
    public function deleted(BroadcastMessage $broadcastMessage): void
    {
        //
    }

    /**
     * Handle the BroadcastMessage "restored" event.
     */
    public function restored(BroadcastMessage $broadcastMessage): void
    {
        //
    }

    /**
     * Handle the BroadcastMessage "force deleted" event.
     */
    public function forceDeleted(BroadcastMessage $broadcastMessage): void
    {
        //
    }
}
