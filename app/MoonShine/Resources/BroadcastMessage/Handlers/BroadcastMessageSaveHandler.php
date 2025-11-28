<?php

namespace App\MoonShine\Resources\BroadcastMessage\Handlers;

use App\Jobs\SendBroadcastJob;
use App\Models\BroadcastMessage;
use Illuminate\Support\Facades\Log;

final readonly class BroadcastMessageSaveHandler
{
    public function __invoke(BroadcastMessage $model, array $data): BroadcastMessage
    {
        // 1. Вытащим ID юзеров
        $users = $data['telegramUsers'] ?? [];

        // 2. Удаляем из входящих данных
        unset($data['telegramUsers'], $data['telegram_users']);

        // 3. Удаляем из самой модели (важно!)
        $model->offsetUnset('telegramUsers');
        $model->offsetUnset('telegram_users');

        // 4. Заполняем только существующие колонки
        $model->fill($data);
        $model->save();

        // 5. Сохраняем связь BelongsToMany
        $model->telegramUsers()->sync($users);
        SendBroadcastJob::dispatch($model->id, $users->toArray())->onQueue('broadcast.telegram');;
        return $model;
    }
}
