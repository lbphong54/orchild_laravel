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
                'name' => 'Buffet Hải sản',
                'description' => 'Buffet với nhiều món hải sản tươi sống đa dạng.',
            ],
            [
                'name' => 'Buffet Lẩu',
                'description' => 'Buffet các loại lẩu: lẩu Thái, lẩu nấm, lẩu Hàn Quốc...',
            ],
            [
                'name' => 'Buffet Nướng',
                'description' => 'Buffet các món nướng phong phú như bò, gà, hải sản...',
            ],
            [
                'name' => 'Buffet Chay',
                'description' => 'Buffet các món chay thanh đạm, phong phú từ rau củ quả.',
            ],
            [
                'name' => 'Buffet Quốc tế',
                'description' => 'Buffet đa dạng phong cách Âu, Á, Việt, Nhật, Hàn...',
            ],
            [
                'name' => 'Buffet Trưa',
                'description' => 'Buffet phục vụ buổi trưa với nhiều món ăn phù hợp công sở.',
            ],
            [
                'name' => 'Buffet Tối',
                'description' => 'Buffet phục vụ buổi tối với thực đơn mở rộng và phong phú.',
            ],
            [
                'name' => 'Buffet Tráng miệng',
                'description' => 'Buffet các loại bánh ngọt, trái cây, kem và món tráng miệng.',
            ],
            [
                'name' => 'Buffet Gia đình',
                'description' => 'Buffet phù hợp cho gia đình, nhiều sự lựa chọn cho trẻ em.',
            ],
            [
                'name' => 'Buffet Đồ nướng & Lẩu',
                'description' => 'Kết hợp buffet lẩu và nướng với nhiều loại nước chấm đặc sản.',
            ],
        ];

        foreach ($types as $type) {
            RestaurantType::create($type);
        }
    }
}