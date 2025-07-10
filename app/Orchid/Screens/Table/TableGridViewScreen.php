<?php

namespace App\Orchid\Screens\Table;

use App\Models\Restaurant;
use App\Models\RestaurantTable;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Illuminate\Support\Facades\Auth;
use App\Orchid\Layouts\Table\TableGridViewLayout;

class TableGridViewScreen extends Screen
{
    public function name(): ?string
    {
        return 'Quản lý bàn';
    }

    public function description(): ?string
    {
        return 'Hiển thị các bàn dạng lưới trực quan';
    }

    public function query(): array
    {
        $restauranId = Restaurant::query()->where('user_id', Auth::user()->id)->first()->id;
        if ($restauranId) {
            return [
                'tables' => RestaurantTable::where('restaurant_id', $restauranId)
                    ->orderBy('name')
                    ->get()
            ];
        }
        return [
            'tables' => []
        ];
    }

    public function commandBar(): array
    {
        return [
            Button::make('Thêm bàn mới')
                ->icon('plus')
                ->method('showCreateModal')
                ->class('btn btn-primary')
        ];
    }

    public function layout(): array
    {
        return [
            new TableGridViewLayout(),
        ];
    }

    public function showCreateModal()
    {
        return redirect()->route('platform.tables.create');
    }
}
