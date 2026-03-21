<?php

namespace App\Services\Telegram;

use App\Models\TelegramUser;
use Illuminate\Support\Facades\Log;
use App\Services\Telegram\TelegramApiFactory;
use GuzzleHttp\Exception\GuzzleException;

class TelegramUserService
{
    /**
     * Сохраняем или обновляем пользователя при любом взаимодействии
     */
    public static function syncUser($user): TelegramUser
    {
        $data = [
            'username'      => $user->username ?? null,
            'first_name'    => $user->first_name ?? null,
            'last_name'     => $user->last_name ?? null,
            'language_code' => $user->language_code ?? null,
            'last_activity' => now(),
        ];


        $telegramUser = TelegramUser::updateOrCreate(
            ['telegram_id' => $user->id],
            $data
        );

        return $telegramUser;
    }

    /**
     * Загружаем аватарку Telegram
     */


    public static function syncAvatar(int $telegramId): ?string
    {
        $api = TelegramApiFactory::make();

        $photos = $api->getUserProfilePhotos([
            'user_id' => $telegramId,
            'limit' => 1
        ]);

        if (empty($photos->photos) || empty($photos->photos[0])) {
            return null;
        }

        $fileId = $photos->photos[0][0]['file_id'] ?? null;
        if (!$fileId) return null;


        $file = $api->getFile(['file_id' => $fileId]);
        $filePath = $file['file_path'] ?? null;
        if (!$filePath) return null;

        $dir = storage_path("app/public/telegram/avatars");
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $localPath = "telegram/avatars/{$telegramId}.jpg";
        $fullPath = storage_path("app/public/{$localPath}");

        $url = "https://api.telegram.org/file/bot" . config('telegram.bots.mybot.token') . "/{$filePath}";

        try {
            $client = TelegramApiFactory::makeGuzzleClient();
            $response = $client->get($url);
            file_put_contents($fullPath, $response->getBody()->getContents());
        } catch (GuzzleException|\Exception $e) {
            Log::error('Failed to download avatar', ['error' => $e->getMessage()]);
            return null;
        }

        TelegramUser::where('telegram_id', $telegramId)->update([
            'avatar_path' => $localPath
        ]);

        return $localPath;
    }
}
