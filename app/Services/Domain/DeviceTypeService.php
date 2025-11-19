<?php

namespace App\Services\Domain;

use App\Models\DeviceType;

class DeviceTypeService
{
    public function getAll(): array
    {
        return \App\Models\DeviceType::all()->toArray();
    }
}

class BrandService
{
    public function getByDeviceType(int $typeId): array
    {
        return \App\Models\Brand::whereHas('deviceTypes', fn($q) => $q->where('device_type_id', $typeId))->get()->toArray();
    }
}

class DeviceModelService
{
    public function getByBrand(int $brandId): array
    {
        return \App\Models\DeviceModel::where('brand_id', $brandId)->get()->toArray();
    }
}
