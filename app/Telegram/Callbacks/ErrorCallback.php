<?php

namespace App\Telegram\Callbacks;

use App\Telegram\Keyboard;
use App\Models\DeviceModel;
use App\Models\ErrorCode;
use Telegram\Bot\Laravel\Facades\Telegram;

class ErrorCallback
{
    public static function handle($callback, $data)
    {
        $action = $data[1];

        if ($action === 'list') {
            $modelId = $data[2];
            $model = DeviceModel::findOrFail($modelId);

            $errors = $model->errorCodes;

            Telegram::editMessageText([
                'chat_id'    => $callback->message->chat->id,
                'message_id' => $callback->message->message_id,
                'text'       => "Ошибки для: {$model->name}",
                'reply_markup' => Keyboard::list($errors, 'error:view')
            ]);
        }

        if ($action === 'view') {
            $errorId = $data[2];
            $error = ErrorCode::findOrFail($errorId);

            Telegram::answerCallbackQuery([
                'callback_query_id' => $callback->id
            ]);

            Telegram::sendMessage([
                'chat_id' => $callback->message->chat->id,
                'text' => "Ошибка *{$error->code}*\n\n{$error->description}",
                'parse_mode' => 'Markdown'
            ]);
        }
    }
}
