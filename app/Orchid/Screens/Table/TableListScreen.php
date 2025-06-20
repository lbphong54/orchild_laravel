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

    public function description(): ?string
    {
        return 'Danh sách các bàn trong nhà hàng';
    }

    public function query(): array
    {
        return [
            'tables' => Table::where('restaurant_id', auth()->user()->restaurant_id)->paginate()
        ];
    }

    public function commandBar(): array
    {
        return [
            Link::make('Thêm bàn mới')
                ->icon('plus')
                ->route('platform.tables.create')
        ];
    }

    public function layout(): array
    {
        return [
            Layout::table('tables', [
                TD::make('name', 'Tên bàn')
                    ->sort()
                    ->filter(TD::FILTER_TEXT)
                    ->render(fn (Table $table) => Link::make($table->name)
                        ->route('platform.tables.edit', $table)),

                TD::make('min_capacity', 'Số người tối thiểu')
                    ->sort()
                    ->filter(TD::FILTER_NUMERIC),

                TD::make('max_capacity', 'Số người tối đa')
                    ->sort()
                    ->filter(TD::FILTER_NUMERIC),

                TD::make('status', 'Trạng thái')
                    ->sort()
                    ->render(fn (Table $table) => view('platform.tables.status', [
                        'status' => $table->status
                    ])),

                TD::make('created_at', 'Ngày tạo')
                    ->sort()
                    ->render(fn (Table $table) => $table->created_at->format('d/m/Y H:i')),

                TD::make(__('Thao tác'))
                    ->align(TD::ALIGN_CENTER)
                    ->width('100px')
                    ->render(fn (Table $table) => view('platform.tables.actions', [
                        'table' => $table
                    ])),
            ])
        ];
    }
} 