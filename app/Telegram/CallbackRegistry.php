<?php

namespace App\Telegram;

use App\Telegram\Callbacks\{
    DeviceTypeCallback,
    BrandCallback,
    ModelCallback,
    ManualCallback,
    ErrorCallback,
    TestModeCallback
};

class CallbackRegistry
{
    public static array $map = [
        'type'             => DeviceTypeCallback::class,
        'brand'            => BrandCallback::class,
        'model'            => ModelCallback::class,
        'manual'           => ManualCallback::class,
        'manual_file'      => ManualCallback::class,
        'manual_file_list' => ManualCallback::class,
        'back_to_type'     => DeviceTypeCallback::class,
        'back_to_brand'    => BrandCallback::class,
        'back_to_model'    => ModelCallback::class,
        'error'            => ErrorCallback::class,
        'testmode'         => TestModeCallback::class,
    ];
}
