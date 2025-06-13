<?php

namespace App\Orchid\Screens\Restaurant;

use App\Models\Restaurant;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Label;

class RestaurantDetailScreen extends Screen
{
    public $restaurant;

    public function name(): ?string
    {
        return 'Chi tiết nhà hàng: ' . $this->restaurant->name;
    }

    public function query(Restaurant $restaurant): array
    {
        return [
            'restaurant' => $restaurant->load(['types', 'amenities']),
        ];
    }

    public function commandBar(): array
    {
        return [];
    }

    public function layout(): array
    {
        return [
            Layout::rows([
                Group::make([
                    Label::make('restaurant.name')
                        ->title('Tên nhà hàng'),
                    Label::make('restaurant.description')
                        ->title('Mô tả'),
                ]),

                Group::make([
                    Label::make('restaurant.address')
                        ->title('Địa chỉ'),
                    Label::make('restaurant.phone')
                        ->title('Số điện thoại'),
                    Label::make('restaurant.email')
                        ->title('Email'),
                ]),

                Group::make([
                    Label::make('restaurant.opening_hours')
                        ->title('Giờ mở cửa'),
                    Label::make('restaurant.closing_hours')
                        ->title('Giờ đóng cửa'),
                    Label::make('restaurant.status')
                        ->title('Trạng thái'),
                    // ->value(fn ($restaurant) => $restaurant->status ? 'Đang hoạt động' : 'Đóng cửa'),
                ]),

                // Group::make([
                //     Label::make('restaurant.types')
                //         ->title('Loại nhà hàng')
                //         ->value(fn ($restaurant) => $restaurant->types->pluck('name')->implode(', ')),
                //     Label::make('restaurant.amenities')
                //         ->title('Tiện ích')
                //         ->value(fn ($restaurant) => $restaurant->amenities->pluck('name')->implode(', ')),
                // ]),
            ]),
        ];
    }
}