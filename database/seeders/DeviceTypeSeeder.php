<?php

namespace Database\Seeders;

use App\Models\DeviceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeviceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Стиральная машина', 'slug' => 'washing-machine'],
            ['name' => 'Холодильник', 'slug' => 'refrigerator'],
            ['name' => 'Посудомоечная машина', 'slug' => 'dishwasher'],
            ['name' => 'Сушильная машина', 'slug' => 'dryer'],
            ['name' => 'Духовой шкаф', 'slug' => 'oven'],
            ['name' => 'Микроволновка', 'slug' => 'microwave'],
            ['name' => 'Кондиционер', 'slug' => 'air-conditioner'],
            ['name' => 'Телевизор', 'slug' => 'tv'],
        ];

        DeviceType::insert($types);
    }
}
