<?php

namespace Database\Seeders;

use App\Models\RestaurantType;
use Illuminate\Database\Seeder;

class RestaurantTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Nhà hàng Việt Nam',
                'description' => 'Phục vụ các món ăn truyền thống Việt Nam',
            ],
            [
                'name' => 'Nhà hàng Nhật Bản',
                'description' => 'Phục vụ các món ăn Nhật Bản như sushi, sashimi, ramen',
            ],
            [
                'name' => 'Nhà hàng Hàn Quốc',
                'description' => 'Phục vụ các món ăn Hàn Quốc như BBQ, kimchi, bibimbap',
            ],
            [
                'name' => 'Nhà hàng Trung Hoa',
                'description' => 'Phục vụ các món ăn Trung Hoa như dimsum, lẩu, mì xào',
            ],
            [
                'name' => 'Nhà hàng Âu',
                'description' => 'Phục vụ các món ăn phương Tây như steak, pasta, pizza',
            ],
            [
                'name' => 'Quán Cafe',
                'description' => 'Phục vụ đồ uống và bánh ngọt',
            ],
            [
                'name' => 'Quán Bar',
                'description' => 'Phục vụ đồ uống có cồn và đồ ăn nhẹ',
            ],
            [
                'name' => 'Nhà hàng Hải sản',
                'description' => 'Chuyên phục vụ các món hải sản tươi sống',
            ],
            [
                'name' => 'Nhà hàng Chay',
                'description' => 'Phục vụ các món ăn chay',
            ],
            [
                'name' => 'Nhà hàng Buffet',
                'description' => 'Phục vụ buffet với nhiều món ăn đa dạng',
            ],
        ];

        foreach ($types as $type) {
            RestaurantType::create($type);
        }
    }
} 