<?php

namespace App\Orchid\Screens\RestaurantType;

use App\Models\RestaurantType;
use Illuminate\Http\Request;
use Orchid\Alert\Toast as AlertToast;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Toast;

class RestaurantTypeListScreen extends Screen
{
    public function name(): ?string
    {
        return 'Quản lý loại nhà hàng';
    }

    public function query(): array
    {
        return [
            'restaurantTypes' => RestaurantType::paginate()
        ];
    }

    public function commandBar(): array
    {
        return [
            ModalToggle::make('Thêm mới')
                ->icon('plus')
                ->modal('createRestaurantTypeModal')
                ->method('createRestaurantType')
        ];
    }

    public function layout(): array
    {
        return [
            Layout::table('restaurantTypes', [
                TD::make('id', 'ID')
                    ->sort()
                    ->filter(),
                TD::make('name', 'Tên loại')
                    ->sort()
                    ->filter()
                    ->render(fn (RestaurantType $type) => Link::make($type->name)
                        ->route('platform.restaurant-type.edit', $type)),
                TD::make('description', 'Mô tả')
                    ->sort()
                    ->filter(),
                TD::make('created_at', 'Ngày tạo')
                    ->sort()
                    ->render(fn (RestaurantType $type) => $type->created_at->format('d/m/Y H:i')),
            ]),

            Layout::modal('createRestaurantTypeModal', [
                Layout::rows([
                    Input::make('name')
                        ->title('Tên loại')
                        ->required(),
                    TextArea::make('description')
                        ->title('Mô tả') 
                        ->required(),
                ])
            ])
                ->title('Thêm loại nhà hàng')
                ->applyButton('Thêm')
        ];
    }

    public function createRestaurantType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        RestaurantType::create($request->all());

        Toast::success('Thêm loại nhà hàng thành công');
    }
} 