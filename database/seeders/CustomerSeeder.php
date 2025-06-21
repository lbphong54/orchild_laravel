<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'full_name' => 'Nguyễn Văn A',
                'email' => 'nguyenvana@gmail.com',
                'phone' => '0123456789',
                'password' => Hash::make('password'),
            ],
            [
                'full_name' => 'Trần Thị B',
                'email' => 'tranthib@gmail.com',
                'phone' => '0987654321',
                'password' => Hash::make('password'),
            ],
            [
                'full_name' => 'Lê Văn C',
                'email' => 'levanc@gmail.com',
                'phone' => '0369852147',
                'password' => Hash::make('password'),
            ],
            [
                'full_name' => 'Phạm Thị D',
                'email' => 'phamthid@gmail.com',
                'phone' => '0852147963',
                'password' => Hash::make('password'),
            ],
            [
                'full_name' => 'Hoàng Văn E',
                'email' => 'hoangvane@gmail.com',
                'phone' => '0741258963',
                'password' => Hash::make('password'),
            ],
            [
                'full_name' => 'Vũ Thị F',
                'email' => 'vuthif@gmail.com',
                'phone' => '0963258741',
                'password' => Hash::make('password'),
            ],
            [
                'full_name' => 'Đặng Văn G',
                'email' => 'dangvang@gmail.com',
                'phone' => '0147852369',
                'password' => Hash::make('password'),
            ],
            [
                'full_name' => 'Bùi Thị H',
                'email' => 'buithih@gmail.com',
                'phone' => '0258963147',
                'password' => Hash::make('password'),
            ],
            [
                'full_name' => 'Đỗ Văn I',
                'email' => 'dovani@gmail.com',
                'phone' => '0369852147',
                'password' => Hash::make('password'),
            ],
            [
                'full_name' => 'Hồ Thị K',
                'email' => 'hothik@gmail.com',
                'phone' => '0478963251',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}