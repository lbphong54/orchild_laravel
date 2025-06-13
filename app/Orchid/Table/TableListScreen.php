<?php

namespace App\Orchid\Screens\Table;

use App\Models\Table;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;

class TableListScreen extends Screen
{
    public function name(): ?string
    {
        return 'Quản lý bàn';
    }

    public function query(): array
    {
        return [
            'tables' => Table::with('restaurant')->paginate()
        ];
    }

    public function commandBar(): array
    {
        return [
            Link::make('Thêm mới')
                ->icon('plus')
                ->route('platform.table.edit')
        ];
    }

    public function layout(): array
    {
        return [
            Layout::table('tables', [
                TD::make('id', 'ID')->sort()->filter(),
                TD::make('name', 'Tên bàn')->sort()->filter(),
                TD::make('capacity', 'Sức chứa')->sort()->filter(),
                TD::make('restaurant.name', 'Nhà hàng')->sort()->filter(),
                TD::make('status', 'Trạng thái')->sort()->filter(),
            ]),
        ];
    }
}