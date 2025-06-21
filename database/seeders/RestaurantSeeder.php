<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\RestaurantType;
use App\Models\User;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = User::pluck('id')->toArray();
        
        // Nếu không có user nào, tạo một user mặc định hoặc bỏ qua user_id
        if (empty($userIds)) {
            $userIds = [1]; // Giả sử user ID 1 tồn tại
        }
        
        $restaurants = [
            [
                'name' => 'Nhà Hàng Việt Nam Sài Gòn',
                'description' => 'Nhà hàng phục vụ các món ăn truyền thống Việt Nam',
                'address' => '123 Nguyễn Huệ, Quận 1, TP.HCM',
                'phone' => '0281234567',
                'email' => 'info@vietnamsaigon.com',
                'price_range' => '200,000 - 500,000 VNĐ',
                'user_id' => $userIds[array_rand($userIds)],
            ],
            [
                'name' => 'Sushi Master',
                'description' => 'Nhà hàng Nhật Bản với các món sushi tươi ngon',
                'address' => '456 Lê Lợi, Quận 1, TP.HCM',
                'phone' => '0282345678',
                'email' => 'info@sushimaster.com',
                'price_range' => '300,000 - 800,000 VNĐ',
                'user_id' => $userIds[array_rand($userIds)],
            ],
            [
                'name' => 'Korean BBQ House',
                'description' => 'Nhà hàng Hàn Quốc với các món nướng đặc trưng',
                'address' => '789 Đồng Khởi, Quận 1, TP.HCM',
                'phone' => '0283456789',
                'email' => 'info@koreanbbq.com',
                'price_range' => '250,000 - 600,000 VNĐ',
                'user_id' => $userIds[array_rand($userIds)],
            ],
            [
                'name' => 'Dim Sum Palace',
                'description' => 'Nhà hàng Trung Hoa với các món dimsum hấp dẫn',
                'address' => '321 Nguyễn Du, Quận 1, TP.HCM',
                'phone' => '0284567890',
                'email' => 'info@dimsum.com',
                'price_range' => '200,000 - 500,000 VNĐ',
                'user_id' => $userIds[array_rand($userIds)],
            ],
            [
                'name' => 'Steak House',
                'description' => 'Nhà hàng Âu với các món bò nhập khẩu',
                'address' => '654 Lê Duẩn, Quận 1, TP.HCM',
                'phone' => '0285678901',
                'email' => 'info@steakhouse.com',
                'price_range' => '400,000 - 1,000,000 VNĐ',
                'user_id' => $userIds[array_rand($userIds)],
            ],
            [
                'name' => 'The Coffee House',
                'description' => 'Quán cafe với không gian thoáng đãng',
                'address' => '987 Nguyễn Huệ, Quận 1, TP.HCM',
                'phone' => '0286789012',
                'email' => 'info@coffeehouse.com',
                'price_range' => '50,000 - 150,000 VNĐ',
                'user_id' => $userIds[array_rand($userIds)],
            ],
            [
                'name' => 'Sky Bar',
                'description' => 'Bar trên tầng thượng với view toàn thành phố',
                'address' => '147 Bùi Viện, Quận 1, TP.HCM',
                'phone' => '0287890123',
                'email' => 'info@skybar.com',
                'price_range' => '200,000 - 500,000 VNĐ',
                'user_id' => $userIds[array_rand($userIds)],
            ],
            [
                'name' => 'Ocean Seafood',
                'description' => 'Nhà hàng hải sản tươi sống',
                'address' => '258 Võ Văn Tần, Quận 3, TP.HCM',
                'phone' => '0288901234',
                'email' => 'info@oceanseafood.com',
                'price_range' => '300,000 - 800,000 VNĐ',
                'user_id' => $userIds[array_rand($userIds)],
            ],
            [
                'name' => 'Vegetarian Paradise',
                'description' => 'Nhà hàng chay với các món ăn sáng tạo',
                'address' => '369 Lê Văn Sỹ, Quận 3, TP.HCM',
                'phone' => '0289012345',
                'email' => 'info@vegetarian.com',
                'price_range' => '100,000 - 300,000 VNĐ',
                'user_id' => $userIds[array_rand($userIds)],
            ],
            [
                'name' => 'Grand Buffet',
                'description' => 'Nhà hàng buffet với hơn 100 món ăn',
                'address' => '741 Nguyễn Thị Minh Khai, Quận 3, TP.HCM',
                'phone' => '0280123456',
                'email' => 'info@grandbuffet.com',
                'price_range' => '500,000 - 1,000,000 VNĐ',
                'user_id' => $userIds[array_rand($userIds)],
            ],
        ];

        foreach ($restaurants as $restaurant) {
            Restaurant::create($restaurant);
        }
    }
} 