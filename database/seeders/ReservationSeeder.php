<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Customer;
use App\Models\ReservationTable;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
        $customers = Customer::all();
        $restaurants = Restaurant::all();

        for ($i = 0; $i < 50; $i++) {
            $customer = $customers->random();
            $restaurant = $restaurants->random();
            $status = $statuses[array_rand($statuses)];
            $date = now()->addDays(rand(-30, 30));
            $time = rand(10, 21) . ':' . (rand(0, 1) ? '00' : '30');

            $reservation =Reservation::create([
                'customer_id' => $customer->id,
                'restaurant_id' => $restaurant->id,
                // 'reservation_date' => $date->format('Y-m-d'),
                'reservation_time' => $date->format('Y-m-d H:i:s'),
                'num_adults' => rand(1, 10),
                'num_children' => rand(1, 10),
                'status' => $status,
                'special_request' => rand(0, 1) ? 'Ghi chú đặc biệt cho đơn hàng #' . ($i + 1) : null,
            ]);

            ReservationTable::create([
                'reservation_id' => $reservation->id,
                'restaurant_table_id' => $restaurant->restaurantTables()->inRandomOrder()->first()->id,
                'from_time' => $date->format('Y-m-d H:i:s'),
                'to_time' => $date->addHours(rand(1, 3))->format('Y-m-d H:i:s'),
            ]);
        }
    }
} 