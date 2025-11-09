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
        $brands = [
            // Washing Machines (id = 1)
            ['device_type_id' => 1, 'name' => 'Samsung', 'slug' => 'samsung', 'country' => 'South Korea'],
            ['device_type_id' => 1, 'name' => 'LG', 'slug' => 'lg', 'country' => 'South Korea'],
            ['device_type_id' => 1, 'name' => 'Bosch', 'slug' => 'bosch', 'country' => 'Germany'],

            // Refrigerators (id = 2)
            ['device_type_id' => 2, 'name' => 'Whirlpool', 'slug' => 'whirlpool', 'country' => 'USA'],
            ['device_type_id' => 2, 'name' => 'Haier', 'slug' => 'haier', 'country' => 'China'],

            // TV (id = 8)
            ['device_type_id' => 8, 'name' => 'Sony', 'slug' => 'sony', 'country' => 'Japan'],
            ['device_type_id' => 8, 'name' => 'Panasonic', 'slug' => 'panasonic', 'country' => 'Japan'],
        ];

        Brand::insert($brands);
    }
}
