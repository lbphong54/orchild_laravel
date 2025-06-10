<?php

namespace App\Orchid\Screens\RestaurantType;

use App\Models\RestaurantType;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;

class RestaurantTypeEditScreen extends Screen
{
    public $restaurantType;

    public function name(): ?string
    {
        return $this->restaurantType->exists ? 'Chỉnh sửa loại nhà hàng' : 'Thêm loại nhà hàng mới';
    }

    public function query(RestaurantType $restaurantType): array
    {
        return [
            'restaurantType' => $restaurantType
        ];
    }

    public function commandBar(): array
    {
        return [
            Button::make('Lưu')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->restaurantType->exists),

            Button::make('Cập nhật')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->restaurantType->exists),

            Button::make('Xóa')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->restaurantType->exists),
        ];
    }

    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('restaurantType.name')
                    ->title('Tên loại')
                    ->placeholder('Nhập tên loại nhà hàng')
                    ->required(),

                TextArea::make('restaurantType.description')
                    ->title('Mô tả')
                    ->placeholder('Nhập mô tả')
                    ->rows(3),
            ])
        ];
    }

    public function createOrUpdate(RestaurantType $restaurantType, Request $request)
    {
        $restaurantType->fill($request->get('restaurantType'))->save();

        return redirect()->route('platform.restaurant-type.list');
    }

    public function remove(RestaurantType $restaurantType)
    {
        $restaurantType->delete();

        return redirect()->route('platform.restaurant-type.list');
    }
} 