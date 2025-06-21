<?php

namespace App\Orchid\Screens\Table;

use App\Models\RestaurantTable;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use App\Orchid\Layouts\Table\TableStatsLayout;
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
        return [
            'tables' => RestaurantTable::where('restaurant_id', Auth::user()->restaurant_id)
                ->orderBy('name')
                ->get()
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
            new TableStatsLayout(),
            new TableGridViewLayout(),
        ];
    }

    public function showCreateModal()
    {
        return redirect()->route('platform.tables.create');
    }
} 