<?php

namespace App\Services\Telegram;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Telegram\Bot\Api;
use Telegram\Bot\HttpClients\GuzzleHttpClient;
use Telegram\Bot\HttpClients\HttpClientInterface;

class TelegramApiFactory
{
    public static function make(): Api
    {
        return new Api(
            config('telegram.bots.mybot.token'),
            (bool) config('telegram.async_requests', false),
            self::makeHttpClientHandler(),
            config('telegram.base_bot_url', null)
        );
    }

    public static function makeHttpClientHandler(): HttpClientInterface
    {
        return new GuzzleHttpClient(self::makeGuzzleClient());
    }

    public static function makeGuzzleClient(): Client
    {
        return new Client(self::getGuzzleOptions());
    }

    private static function getGuzzleOptions(): array
    {
        $options = [];

        $proxy = config('telegram.proxy');
        if (!empty($proxy)) {
            $options[RequestOptions::PROXY] = $proxy;
        }

        return $options;
    }
}
