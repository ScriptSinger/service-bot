<?php

namespace App\Services\Domain;

use App\Models\DeviceType;

class DeviceTypeService
{
    public function getAll()
    {
        return DeviceType::orderBy('name')->get();
    }
}
