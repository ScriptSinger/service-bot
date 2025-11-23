<?php

namespace App\Telegram;

use Telegram\Bot\Objects\CallbackQuery;

class CallbackRouter
{
    public static function handle(CallbackQuery $callback)
    {
        $data = explode(':', $callback->data);
        $command = $data[0];

        $handlerClass = CallbackRegistry::$map[$command] ?? null;

        if (!$handlerClass) {
            // если команда не найдена, используем ErrorCallback
            $handlerClass = CallbackRegistry::$map['error'];
        }

        // создаём экземпляр обработчика через контейнер Laravel
        $handler = app($handlerClass);

        // вызываем экземплярный метод handle()
        return $handler->handle($callback, $data);
    }
}
