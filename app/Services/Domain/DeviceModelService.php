<?php

namespace App\Services\Domain;

use App\Models\DeviceModel;

class DeviceModelService
{
    /**
     * Получить все модели конкретного бренда
     */
    public function getByBrand(int $brandId): array
    {
        return DeviceModel::where('brand_id', $brandId)->get()->toArray();
    }

    /**
     * Можно добавить другие методы, например поиск по имени, по году и т.д.
     */
}
