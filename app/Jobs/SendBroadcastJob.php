<?php

namespace App\Jobs;


use App\Models\BroadcastMessage;
use App\Models\TelegramUser;
use Illuminate\Bus\Queueable;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Throwable;


class SendBroadcastJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $broadcast;
    protected $users;

    public function __construct($broadcast, $users)
    {
        $this->broadcast = $broadcast;
        $this->users = $users;
    }

    public function handle(): void
    {
        $api = new Api(config('telegram.bots.mybot.token'));
        $results = [];

        $users = TelegramUser::whereIn('id', $this->users)->get();
        foreach ($users as $user) {
            try {
                $api->sendMessage([
                    'chat_id' => $user->telegram_id,
                    'text' => $this->broadcast->message,
                ]);

                $results[] = ['id' => $user->id, 'status' => 'ok'];
            } catch (Throwable $e) {
                $results[] = ['id' => $user->id, 'status' => 'error', 'error' => $e->getMessage()];
            }
        }

        $this->broadcast->update([
            'status' => 'sent',
            'result' => $results,
        ]);
    }
}
