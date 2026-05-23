<?php

namespace App\Services;

use Aws\S3\S3Client;

class YandexTemporaryUrlService
{
    public static function make(string $key, string $expires = '+10 minutes'): string
    {
        $config = config('filesystems.disks.yandex');

        $client = new S3Client([
            'region' => $config['region'],
            'version' => 'latest',
            'endpoint' => $config['endpoint'],
            'use_path_style_endpoint' => $config['use_path_style_endpoint'],
            'credentials' => [
                'key' => $config['key'],
                'secret' => $config['secret'],
            ],
        ]);

        $cmd = $client->getCommand('GetObject', [
            'Bucket' => $config['bucket'],
            'Key' => $key,
        ]);

        $request = $client->createPresignedRequest($cmd, $expires);

        return (string) $request->getUri();
    }

    public static function makeFromUrl(string $url, string $expires = '+10 minutes'): string
    {
        $host = (string) parse_url($url, PHP_URL_HOST);

        if (! str_contains($host, 'storage.yandexcloud.net')) {
            return $url;
        }

        $path = trim((string) parse_url($url, PHP_URL_PATH), '/');

        if ($path === '') {
            return $url;
        }

        $bucket = trim((string) config('filesystems.disks.yandex.bucket'), '/');

        if ($bucket !== '' && str_starts_with($path, $bucket . '/')) {
            $path = substr($path, strlen($bucket) + 1);
        }

        return $path === '' ? $url : self::make($path, $expires);
    }
}
