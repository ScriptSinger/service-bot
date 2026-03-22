<?php

namespace App\Services\Telegram;

use App\Services\YandexTemporaryUrlService;
use Telegram\Bot\Exceptions\TelegramResponseException;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Throwable;

class NavigationService
{
    /**
     * Кодируем массив контекста в строку callback_data
     */
    public function encode(array $data): string
    {
        $data = array_filter($data, fn($v) => $v !== null && $v !== '');
        return http_build_query($data, '', ';');
    }

    /**
     * Декодируем callback_data обратно в массив
     */
    public function decode(string $raw): array
    {
        parse_str(str_replace(';', '&', $raw), $out);
        return $out ?: [];
    }

    /**
     * Определяем предыдущий шаг для кнопки "Назад"
     */
    public function previousStep(string $step): ?string
    {
        return [

            'manuals' => 'models',
            'models'  => 'brands',
            'brands'  => 'types',
            'types'   => null,
        ][$step] ?? null;
    }

    /**
     * Универсальная кнопка "Назад"
     */
    public function backButton(array $ctx)
    {
        $prev = $this->previousStep($ctx['step'] ?? '');
        if (!$prev) return null;

        $payload = [
            'step'  => $prev,
            'type'  => $ctx['type']  ?? null,
            'brand' => $ctx['brand'] ?? null,
            'model' => $ctx['model'] ?? null,
            'manual' => $ctx['manual'] ?? null,
        ];

        return Keyboard::inlineButton([
            'text' => '⬅️ Назад',
            'callback_data' => $this->encode($payload),
        ]);
    }

    public function sendMessage(int $chatId, string $text, $keyboard)
    {
        try {
            return Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => $text,
                'reply_markup' => $keyboard
            ]);
        } catch (TelegramResponseException $e) {
            \Log::warning('telegram.sendMessage.failed', [
                'chat_id' => $chatId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        } catch (Throwable $e) {
            \Log::error('telegram.sendMessage.error', [
                'chat_id' => $chatId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function editMessage(int $chatId, int $messageId, string $text, $keyboard)
    {
        try {
            return Telegram::editMessageText([
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'text' => $text,
                'reply_markup' => $keyboard
            ]);
        } catch (TelegramResponseException $e) {
            if (str_contains($e->getMessage(), 'message is not modified')) {
                return null;
            }
            \Log::warning('telegram.editMessage.failed', [
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        } catch (Throwable $e) {
            \Log::error('telegram.editMessage.error', [
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }


    /**
     * Генерация клавиатуры типов устройств
     */
    public function buildTypeKeyboard($types)
    {
        $kb = Keyboard::make()->inline();
        foreach ($types as $t) {
            $kb->row([Keyboard::inlineButton([
                'text' => $t->name,
                'callback_data' => $this->encode(['step' => 'brands', 'type' => $t->id]),
            ])]);
        }
        return $kb;
    }

    /**
     * Генерация клавиатуры брендов
     */
    public function buildBrandKeyboard($brands, array $ctx)
    {
        $kb = Keyboard::make()->inline();
        foreach ($brands as $b) {
            $kb->row([Keyboard::inlineButton([
                'text' => $b->name,
                'callback_data' => $this->encode(['step' => 'models', 'type' => $ctx['type'], 'brand' => $b->id]),
            ])]);
        }

        $back = $this->backButton(['step' => 'brands', 'type' => $ctx['type']]);
        if ($back) $kb->row([$back]);

        return $kb;
    }

    /**
     * Генерация клавиатуры моделей
     */
    public function buildModelKeyboard($models, array $ctx)
    {
        $kb = Keyboard::make()->inline();
        foreach ($models as $m) {
            $kb->row([Keyboard::inlineButton([
                'text' => $m->name,
                'callback_data' => $this->encode([
                    'step' => 'manuals',
                    'type' => $ctx['type'],
                    'brand' => $ctx['brand'],
                    'model' => $m->id
                ]),
            ])]);
        }

        $back = $this->backButton([
            'step' => 'models',
            'type' => $ctx['type'],
            'brand' => $ctx['brand']
        ]);

        if ($back) $kb->row([$back]);

        return $kb;
    }

    public function buildManualKeyboard($manuals, array $ctx)
    {
        $kb = Keyboard::make()->inline();

        foreach ($manuals as $manual) {
            foreach ($manual->files as $file) {
                $label = $file->title ?? '📄 Скачать файл';
                if (!empty($file->language)) {
                    $label .= " ({$file->language})";
                }

                $kb->row([
                    Keyboard::inlineButton([
                        'text' => $label,
                        'url' => YandexTemporaryUrlService::make($file->file_url),
                    ])
                ]);
            }
        }

        // Кнопка "Назад" возвращает к списку моделей
        $back = $this->backButton([
            'step'  => 'manuals',
            'type'  => $ctx['type'] ?? null,
            'brand' => $ctx['brand'] ?? null,
            'model' => $ctx['model'] ?? null,
        ]);

        if ($back) {
            $kb->row([$back]);
        }

        return $kb;
    }
}
