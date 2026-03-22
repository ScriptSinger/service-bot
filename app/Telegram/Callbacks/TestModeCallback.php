<?php

namespace App\Telegram\Callbacks;

use App\Models\DeviceModel;
use App\Models\TestMode;
use Telegram\Bot\Exceptions\TelegramResponseException;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Throwable;

class TestModeCallback
{
    public static function handle($callback, $data)
    {
        $action = $data[1];

        if ($action === 'list') {
            $modelId = $data[2];
            $model = DeviceModel::findOrFail($modelId);

            $tests = $model->testModes;

            try {
                Telegram::editMessageText([
                    'chat_id'    => $callback->message->chat->id,
                    'message_id' => $callback->message->message_id,
                    'text'       => "Тестовые режимы для: {$model->name}",
                    'reply_markup' => Keyboard::list($tests, 'test:view')
                ]);
            } catch (TelegramResponseException $e) {
                \Log::warning('telegram.editMessage.failed', [
                    'chat_id' => $callback->message->chat->id,
                    'message_id' => $callback->message->message_id,
                    'error' => $e->getMessage(),
                ]);
                throw $e;
            } catch (Throwable $e) {
                \Log::error('telegram.editMessage.error', [
                    'chat_id' => $callback->message->chat->id,
                    'message_id' => $callback->message->message_id,
                    'error' => $e->getMessage(),
                ]);
                throw $e;
            }
        }

        if ($action === 'view') {
            $testId = $data[2];
            $test = TestMode::findOrFail($testId);

            $text = "🔧 *Тестовый режим*\n"
                . "\nВход: {$test->entry_combination}"
                . "\nВыход: {$test->exit_combination}"
                . "\n\n{$test->notes}";

            try {
                Telegram::sendMessage([
                    'chat_id' => $callback->message->chat->id,
                    'text' => $text,
                    'parse_mode' => 'Markdown'
                ]);
            } catch (TelegramResponseException $e) {
                \Log::warning('telegram.sendMessage.failed', [
                    'chat_id' => $callback->message->chat->id,
                    'error' => $e->getMessage(),
                ]);
                throw $e;
            } catch (Throwable $e) {
                \Log::error('telegram.sendMessage.error', [
                    'chat_id' => $callback->message->chat->id,
                    'error' => $e->getMessage(),
                ]);
                throw $e;
            }
        }
    }
}
