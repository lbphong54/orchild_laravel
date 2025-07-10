<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Orchid\Platform\Models\User;
use Orchid\Platform\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo role admin nếu chưa tồn tại
        $adminRole =
            Role::insert([
                [
                    'name' => 'Administrator',
                    'slug' => 'admin',
                    'permissions' => [
                        'platform.index' => true,
                        'platform.systems.roles' => true,
                        'platform.systems.users' => true,
                        'platform.systems.attachment' => true,
                        'platform.systems.settings' => true,
                        'platform.review.manage' => true,
                        'platform.restaurant.manage' => true,
                        'platform.restaurant-type.manage' => true,
                        'platform.stats' => true,
                    ],
                ],
                [
                    'name' => 'AdminRestaurant',
                    'slug' => 'admin-restaurant',
                    'permissions' => [
                        'platform.index' => true,
                        'platform.restaurant.profile' => true,
                        'platform.review.manage' => true,
                        'platform.tables.manage' => true,
                    ],
                ],
            ]);

        // Tạo user admin
        $admin = User::firstOrCreate([
            'email' => 'admin@admin.com',
        ], [
            'name' => 'Administrator',
            'password' => Hash::make('password'),
            'permissions' => [
                'platform.index' => true,
                'platform.systems.roles' => true,
                'platform.systems.users' => true,
                'platform.systems.attachment' => true,
                'platform.systems.settings' => true,
                'platform.products' => true,
                'platform.categories' => true,
                'platform.types' => true,
                'platform.colors' => true,
                'platform.sizes' => true,
                'platform.partners' => true,
            ],
        ]);

        // Gán role admin cho user
        $admin->addRole($adminRole);
    }
}
