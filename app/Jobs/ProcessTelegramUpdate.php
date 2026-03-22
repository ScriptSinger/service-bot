<?php

namespace App\Jobs;

use App\Telegram\CallbackRouter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

class ProcessTelegramUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function handle(): void
    {
        $startedAt = microtime(true);
        $updateId = $this->payload['update_id'] ?? null;
        $type = array_key_first(array_intersect_key($this->payload, array_flip([
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

        $update = new Update($this->payload);

        Telegram::processCommand($update);

        if ($update->callbackQuery) {
            CallbackRouter::handle($update->callbackQuery);
        }

        Log::info('telegram.webhook.processed', [
            'update_id' => $updateId,
            'type' => $type,
            'ms' => (int) ((microtime(true) - $startedAt) * 1000),
        ]);
    }
}
