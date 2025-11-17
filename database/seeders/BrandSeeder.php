<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Washing Machines
            ['name' => 'Samsung', 'slug' => 'samsung', 'country' => 'South Korea', 'device_type_ids' => [1]],
            ['name' => 'LG', 'slug' => 'lg', 'country' => 'South Korea', 'device_type_ids' => [1]],
            ['name' => 'Bosch', 'slug' => 'bosch', 'country' => 'Germany', 'device_type_ids' => [1]],

            // Refrigerators
            ['name' => 'Whirlpool', 'slug' => 'whirlpool', 'country' => 'USA', 'device_type_ids' => [2]],
            ['name' => 'Haier', 'slug' => 'haier', 'country' => 'China', 'device_type_ids' => [2]],

            // TV
            ['name' => 'Sony', 'slug' => 'sony', 'country' => 'Japan', 'device_type_ids' => [8]],
            ['name' => 'Panasonic', 'slug' => 'panasonic', 'country' => 'Japan', 'device_type_ids' => [8]],
        ];

        foreach ($data as $brandData) {
            // Создаём бренд
            $deviceTypeIds = $brandData['device_type_ids'];
            unset($brandData['device_type_ids']);

            $brand = Brand::create($brandData);

            // Привязываем device types через pivot
            $brand->deviceTypes()->attach($deviceTypeIds);
        }
    }
}
