<?php

namespace App\Orchid\Screens\Restaurant;

use App\Models\Restaurant;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;

class RestaurantListScreen extends Screen
{
    public function name(): ?string
    {
        return 'Quản lý nhà hàng';
    }

    public function query(): array
    {
        return [
            'restaurants' => Restaurant::with('types')
                // ->filters()
                // ->defaultSort('id', 'desc')
                ->paginate()
        ];
    }

    public function commandBar(): array
    {
        return [
            // Link::make('Thêm mới')
            //     ->icon('plus')
            //     ->route('platform.restaurant.edit')
        ];
    }

    public function layout(): array
    {
        return [
            Layout::table('restaurants', [
                TD::make('id', 'ID')
                    ->sort()
                    ->filter(),
                TD::make('name', 'Tên nhà hàng')
                    ->sort()
                    ->filter()
                    ->render(fn (Restaurant $restaurant) => Link::make($restaurant->name)
                        ->route('platform.restaurant.detail', $restaurant)),
                TD::make('address', 'Địa chỉ')
                    ->sort()
                    ->filter(),
                TD::make('phone', 'Số điện thoại')
                    ->sort()
                    ->filter(),
                TD::make('email', 'Email')
                    ->sort()
                    ->filter(),
                TD::make('status', 'Trạng thái')
                    ->sort()
                    ->filter()
                    ->render(fn (Restaurant $restaurant) => $restaurant->status ? 'Hoạt động' : 'Không hoạt động'),
                TD::make('created_at', 'Ngày tạo')
                    ->sort()
                    ->render(fn (Restaurant $restaurant) => $restaurant->created_at->format('d/m/Y H:i')),
            ])
        ];
    }
} 