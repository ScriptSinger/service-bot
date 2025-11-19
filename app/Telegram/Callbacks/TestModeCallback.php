<?php

namespace App\Telegram\Callbacks;

use Telegram\Bot\Facades\Telegram;
use App\Models\DeviceModel;
use App\Models\TestMode;
use App\Telegram\Keyboard;

class TestModeCallback
{
    public static function handle($callback, $data)
    {
        $action = $data[1];

        if ($action === 'list') {
            $modelId = $data[2];
            $model = DeviceModel::findOrFail($modelId);

            $tests = $model->testModes;

            Telegram::editMessageText([
                'chat_id'    => $callback->message->chat->id,
                'message_id' => $callback->message->message_id,
                'text'       => "Тестовые режимы для: {$model->name}",
                'reply_markup' => Keyboard::list($tests, 'test:view')
            ]);
        }

        if ($action === 'view') {
            $testId = $data[2];
            $test = TestMode::findOrFail($testId);

            $text = "🔧 *Тестовый режим*\n"
                . "\nВход: {$test->entry_combination}"
                . "\nВыход: {$test->exit_combination}"
                . "\n\n{$test->notes}";

            Telegram::sendMessage([
                'chat_id' => $callback->message->chat->id,
                'text' => $text,
                'parse_mode' => 'Markdown'
            ]);
        }
    }
}
