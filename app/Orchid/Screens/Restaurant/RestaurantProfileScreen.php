<?php

namespace App\Orchid\Screens\Restaurant;

use App\Models\Restaurant;
use App\Models\RestaurantType;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Upload;
use Orchid\Support\Facades\Toast;

class RestaurantProfileScreen extends Screen
{
    public $restaurant;

    public function name(): ?string
    {
        return 'Thông tin nhà hàng';
    }

    public function description(): ?string
    {
        return 'Cập nhật thông tin nhà hàng của bạn';
    }

    public function query(Restaurant $restaurant): array
    {
        return [
            'restaurant' => $restaurant,
            'types' => RestaurantType::all(),
            'amenities' => Amenity::all(),
        ];
    }

    public function commandBar(): array
    {
        return [
            Button::make('Cập nhật')
                ->icon('note')
                ->method('update')
        ];
    }

    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('restaurant.name')
                    ->title('Tên nhà hàng')
                    ->placeholder('Nhập tên nhà hàng')
                    ->required(),

                TextArea::make('restaurant.description')
                    ->title('Mô tả')
                    ->placeholder('Nhập mô tả')
                    ->rows(3),

                Input::make('restaurant.address')
                    ->title('Địa chỉ')
                    ->placeholder('Nhập địa chỉ')
                    ->required(),

                Input::make('restaurant.phone')
                    ->title('Số điện thoại')
                    ->placeholder('Nhập số điện thoại')
                    ->required(),

                Input::make('restaurant.email')
                    ->title('Email')
                    ->placeholder('Nhập email')
                    ->type('email')
                    ->required(),

                Input::make('restaurant.opening_hours')
                    ->title('Giờ mở cửa')
                    ->type('time')
                    ->required(),

                Input::make('restaurant.closing_hours')
                    ->title('Giờ đóng cửa')
                    ->type('time')
                    ->required(),

                Select::make('restaurant.types.')
                    ->fromModel(RestaurantType::class, 'name')
                    ->title('Loại nhà hàng')
                    ->multiple()
                    ->help('Chọn các loại nhà hàng'),

                Select::make('restaurant.amenities.')
                    ->fromModel(Amenity::class, 'name')
                    ->title('Tiện ích')
                    ->multiple()
                    ->help('Chọn các tiện ích'),

                Upload::make('restaurant.images')
                    ->title('Hình ảnh')
                    ->multiple()
                    ->maxFiles(5)
                    ->help('Tải lên hình ảnh nhà hàng (tối đa 5 ảnh)'),
            ])
        ];
    }

    public function update(Restaurant $restaurant, Request $request)
    {
        $restaurant->fill($request->get('restaurant'))->save();
        
        $restaurant->types()->sync($request->get('restaurant.types'));
        $restaurant->amenities()->sync($request->get('restaurant.amenities'));

        Toast::info('Thông tin nhà hàng đã được cập nhật thành công.');
    }
} 