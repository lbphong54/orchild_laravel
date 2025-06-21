<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\RestaurantTable;
use Illuminate\Database\Seeder;

class RestaurantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $restaurants = Restaurant::all();

        foreach ($restaurants as $restaurant) {
            // Tạo các loại bàn khác nhau cho mỗi nhà hàng
            $tables = [
                // Bàn 2 người
                [
                    'name' => 'Bàn 2-1',
                    'min_capacity' => 2,
                    'max_capacity' => 2,
                    'status' => 'available',
                ],
                [
                    'name' => 'Bàn 2-2',
                    'min_capacity' => 2,
                    'max_capacity' => 2,
                    'status' => 'available',
                ],
                [
                    'name' => 'Bàn 2-3',
                    'min_capacity' => 2,
                    'max_capacity' => 2,
                    'status' => 'available',
                ],
                [
                    'name' => 'Bàn 2-4',
                    'min_capacity' => 2,
                    'max_capacity' => 2,
                    'status' => 'occupied',
                ],

                // Bàn 4 người
                [
                    'name' => 'Bàn 4-1',
                    'min_capacity' => 4,
                    'max_capacity' => 4,
                    'status' => 'available',
                ],
                [
                    'name' => 'Bàn 4-2',
                    'min_capacity' => 4,
                    'max_capacity' => 4,
                    'status' => 'available',
                ],
                [
                    'name' => 'Bàn 4-3',
                    'min_capacity' => 4,
                    'max_capacity' => 4,
                    'status' => 'reserved',
                ],
                [
                    'name' => 'Bàn 4-4',
                    'min_capacity' => 4,
                    'max_capacity' => 4,
                    'status' => 'occupied',
                ],

                // Bàn 6 người
                [
                    'name' => 'Bàn 6-1',
                    'min_capacity' => 6,
                    'max_capacity' => 6,
                    'status' => 'available',
                ],
                [
                    'name' => 'Bàn 6-2',
                    'min_capacity' => 6,
                    'max_capacity' => 6,
                    'status' => 'available',
                ],

                // Bàn 8 người
                [
                    'name' => 'Bàn 8-1',
                    'min_capacity' => 8,
                    'max_capacity' => 8,
                    'status' => 'available',
                ],

                // Bàn VIP (10 người)
                [
                    'name' => 'VIP 1',
                    'min_capacity' => 10,
                    'max_capacity' => 10,
                    'status' => 'available',
                ],
                [
                    'name' => 'VIP 2',
                    'min_capacity' => 10,
                    'max_capacity' => 10,
                    'status' => 'reserved',
                ],

                // Bàn lớn (12 người)
                [
                    'name' => 'Bàn lớn 1',
                    'min_capacity' => 12,
                    'max_capacity' => 12,
                    'status' => 'available',
                ],
            ];

            foreach ($tables as $tableData) {
                RestaurantTable::create([
                    'restaurant_id' => $restaurant->id,
                    'name' => $tableData['name'],
                    'min_capacity' => $tableData['min_capacity'],
                    'max_capacity' => $tableData['max_capacity'],
                    'status' => $tableData['status'],
                ]);
            }
        }
    }
} 