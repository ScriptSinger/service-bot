<?php

// Подключаем Composer автолоадер
require __DIR__ . '/vendor/autoload.php';

use App\MoonShine\Resources\BroadcastMessage\BroadcastMessageResource;

// Создаём ReflectionClass
$reflection = new ReflectionClass(BroadcastMessageResource::class);
$attributes = $reflection->getAttributes();

foreach ($attributes as $attribute) {
    echo "[MoonShine] Атрибут: " . $attribute->getName() . PHP_EOL;
    echo "[MoonShine] Аргументы: ";
    print_r($attribute->getArguments());
    echo PHP_EOL;
}
