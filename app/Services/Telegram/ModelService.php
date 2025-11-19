<?php

namespace App\Services\Telegram;

use App\Models\DeviceModel;

class ModelService
{
    public static function getModelsByBrand($brandId)
    {
        return DeviceModel::where('brand_id', $brandId)
            ->orderBy('name')
            ->get();
    }
}
