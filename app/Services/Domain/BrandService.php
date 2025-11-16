<?php

namespace App\Services\Domain;

use App\Models\Brand;

class BrandService
{
    public function getByDeviceTypeSlug($slug)
    {
        return Brand::whereHas('deviceType', fn($q) => $q->where('slug', $slug))->get();
    }

    public function getById($id)
    {
        return Brand::findOrFail($id);
    }
}
