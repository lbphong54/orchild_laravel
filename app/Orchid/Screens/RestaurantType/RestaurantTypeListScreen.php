<?php

namespace App\Orchid\Screens\RestaurantType;

use App\Models\RestaurantType;
use Illuminate\Http\Request;
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
            'restaurantTypes' => RestaurantType::orderBy('id', 'desc')->paginate()
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
                    ->filter(),
                // TD::make('created_at', 'Ngày tạo')
                //     ->sort()
                //     ->render(fn(RestaurantType $type) => $type->created_at->format('d/m/Y H:i')),
                TD::make('')
                    ->align(TD::ALIGN_CENTER)
                    ->width('100px')
                    ->render(
                        fn(RestaurantType $type) => ModalToggle::make('')
                            ->icon('eye')
                            ->modal('editRestaurantTypeModal')
                            ->method('editRestaurantType')
                            ->modalTitle('Chỉnh sửa loại nhà hàng')
                            ->asyncParameters([
                                'restaurantType' => $type->id
                            ])
                    ),
            ]),

            Layout::modal('createRestaurantTypeModal', [
                Layout::rows([
                    Input::make('name')
                        ->title('Tên loại')
                        ->required(),
                    TextArea::make('description')
                        ->title('Mô tả'),
                ])
            ])
                ->title('Thêm loại nhà hàng')
                ->applyButton('Thêm'),

            Layout::modal('editRestaurantTypeModal', [
                Layout::rows([
                    Input::make('restaurantType.name')
                        ->title('Tên loại')
                        ->required(),
                    TextArea::make('restaurantType.description')
                        ->title('Mô tả'),
                ])
            ])
                ->title('Chỉnh sửa loại nhà hàng')
                ->applyButton('Cập nhật')
                ->async('asyncGetRestaurantType')
        ];
    }

    public function asyncGetRestaurantType(RestaurantType $restaurantType)
    {
        return [
            'restaurantType' => $restaurantType
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

    public function editRestaurantType(Request $request, RestaurantType $restaurantType)
    {
        $request->validate([
            'restaurantType.name' => 'required|string|max:255',
            'restaurantType.description' => 'nullable|string',
        ]);

        $restaurantType->update([
            'name' => $request->input('restaurantType.name'),
            'description' => $request->input('restaurantType.description')
        ]);

        Toast::success('Cập nhật loại nhà hàng thành công');
    }
}