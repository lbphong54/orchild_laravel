<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Amenity;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amenities = [
            ['name' => 'Wifi miễn phí'],
            ['name' => 'Bãi đỗ xe'],
            ['name' => 'Máy lạnh'],
            ['name' => 'Phòng riêng'],
            ['name' => 'Chỗ chơi trẻ em'],
            ['name' => 'Thanh toán thẻ'],
            ['name' => 'Khu vực hút thuốc'],
            ['name' => 'Giao hàng tận nơi'],
            ['name' => 'Có phục vụ rượu/bia'],
            ['name' => 'Sân vườn ngoài trời'],
            ['name' => 'Hát karaoke'],
            ['name' => 'Phù hợp tổ chức tiệc'],
            ['name' => 'Thú cưng được phép'],
            ['name' => 'Thang máy'],
            ['name' => 'Hỗ trợ người khuyết tật'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::create($amenity);
        }
    }
}