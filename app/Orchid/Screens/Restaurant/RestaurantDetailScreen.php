<?php

namespace App\Orchid\Screens\Restaurant;

use App\Models\Restaurant;
use Illuminate\Support\Facades\Log;
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
            'restaurant' => $restaurant->load(['types', 'restaurant_amenities']),
        ];
    }

    public function commandBar(): array
    {
        return [];
    }

    public function layout(): array
    {
        $restaurant = $this->restaurant;
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
                        ->title('Giờ mở cửa')
                        ->value(function () use ($restaurant) {
                            $openingHours = $restaurant['opening_hours'];
                            if (empty($openingHours) || !is_array($openingHours)) {
                                return 'Chưa cập nhật';
                            }


                            $days = [
                                'monday' => 'Thứ 2',
                                'tuesday' => 'Thứ 3',
                                'wednesday' => 'Thứ 4',
                                'thursday' => 'Thứ 5',
                                'friday' => 'Thứ 6',
                                'saturday' => 'Thứ 7',
                                'sunday' => 'Chủ nhật'
                            ];
                            $formatted = [];
                            foreach ($openingHours as $day => $hours) {

                                if (isset($hours['open']) && isset($hours['close'])) {
                                    $formatted[] = ' 📅 ' . $days[$day] . ': ' . $hours['open'] . ' - ' . $hours['close'];
                                }
                            }

                            return empty($formatted) ? 'Chưa cập nhật' : implode($formatted);
                        }),
                    Label::make('restaurant.status')
                        ->title('Trạng thái')
                        ->value(function ($status) {
                            return $status == 'active' ? 'Đang hoạt động' : 'Đóng cửa';
                        }),
                ]),

                Group::make([
                    Label::make('restaurant.types')
                        ->title('Loại nhà hàng')
                        ->value(function ($type, $query) {
                            $res_type = $query['restaurant']['types'] ?? [];
                            return $res_type->pluck('name')->implode(', ');
                        }),
                    Label::make('restaurant.types')
                        ->title('Tiện ích')
                        ->value(function ($type, $query) {
                            $res_type = $query['restaurant']['restaurant_amenities'] ?? [];
                            return $res_type->pluck('name')->implode(', ');
                        }),
                ]),

            ]),
        ];
    }
}
