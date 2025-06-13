<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\Customer;
use App\Models\Restaurant;
use App\Models\Reservation;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();
        $restaurants = Restaurant::all();
        $reservations = Reservation::all();
        $comments = [
            'Nhà hàng rất tốt, đồ ăn ngon và phục vụ chu đáo.',
            'Không gian đẹp, giá cả hợp lý.',
            'Đồ ăn ngon nhưng hơi đắt.',
            'Phục vụ nhanh, nhân viên thân thiện.',
            'Món ăn đặc trưng rất ngon.',
            'Không gian thoáng đãng, view đẹp.',
            'Giá cả phải chăng, đồ ăn ngon.',
            'Nhân viên phục vụ nhiệt tình.',
            'Đồ ăn tươi ngon, đảm bảo vệ sinh.',
            'Không gian ấm cúng, phù hợp cho gia đình.',
        ];

        for ($i = 0; $i < 100; $i++) {
            $customer = $customers->random();
            $restaurant = $restaurants->random();
            $rating = rand(3, 5);
            $comment = $comments[array_rand($comments)];
            $date = now()->subDays(rand(0, 30));
            $reservation = $reservations->random();

            Review::create([
                'customer_id' => $customer->id,
                'restaurant_id' => $restaurant->id,
                'reservation_id' => $reservation->id,
                'rating' => $rating,
                'comment' => $comment,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }
}