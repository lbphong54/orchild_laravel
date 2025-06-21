<?php

namespace App\Orchid\Screens\Table;

use App\Models\Restaurant;
use App\Models\RestaurantTable;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Orchid\Layouts\Table\TableStatsLayout;

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
            'tables' => RestaurantTable::where('restaurant_id', Restaurant::where('user_id', Auth::user()->id)->first()->id)
                ->orderBy('name')
                ->paginate(15)
        ];
    }

    public function commandBar(): array
    {
        return [
            Button::make('Thêm bàn mới')
                ->icon('plus')
                ->method('showCreateModal')
                ->class('btn btn-primary'),

            Link::make('Xem dạng lưới')
                ->icon('grid')
                ->route('platform.tables')
                ->class('btn btn-info'),
        ];
    }

    public function layout(): array
    {
        return [
            new TableStatsLayout(),
            
            Layout::modal('createTableModal', [
                Layout::rows([
                    Input::make('table.name')
                        ->title('Tên bàn')
                        ->placeholder('Nhập tên bàn')
                        ->help('Tên hiển thị của bàn')
                        ->required(),

                    Group::make([
                        Input::make('table.min_capacity')
                            ->type('number')
                            ->title('Số người tối thiểu')
                            ->placeholder('2')
                            ->help('Số lượng người tối thiểu có thể ngồi')
                            ->required(),

                        Input::make('table.max_capacity')
                            ->type('number')
                            ->title('Số người tối đa')
                            ->placeholder('4')
                            ->help('Số lượng người tối đa có thể ngồi')
                            ->required(),
                    ]),

                    Select::make('table.status')
                        ->title('Trạng thái')
                        ->options([
                            'available' => 'Trống',
                            'occupied' => 'Đang sử dụng',
                            'reserved' => 'Đã đặt trước'
                        ])
                        ->help('Trạng thái hiện tại của bàn')
                        ->required(),
                ])
            ])
            ->title('Thêm bàn mới')
            ->applyButton('Tạo bàn')
            ->closeButton('Hủy'),

            Layout::table('tables', [
                TD::make('name', 'Tên bàn')
                    ->sort()
                    ->filter(TD::FILTER_TEXT)
                    ->render(fn (RestaurantTable $table) => Link::make($table->name)
                        ->route('platform.tables.edit', $table)
                        ->class('text-decoration-none')),

                TD::make('capacity', 'Sức chứa')
                    ->render(fn (RestaurantTable $table) => "{$table->min_capacity} - {$table->max_capacity} người")
                    ->sort('min_capacity'),

                TD::make('status', 'Trạng thái')
                    ->sort()
                    ->render(fn (RestaurantTable $table) => view('platform.tables.status', [
                        'status' => $table->status
                    ])),

                TD::make('created_at', 'Ngày tạo')
                    ->sort()
                    ->render(fn (RestaurantTable $table) => $table->created_at->format('d/m/Y H:i')),

                TD::make(__('Thao tác'))
                    ->align(TD::ALIGN_CENTER)
                    ->width('120px')
                    ->render(fn (RestaurantTable $table) => view('platform.tables.actions', [
                        'table' => $table
                    ])),
            ])
        ];
    }

    public function showCreateModal()
    {
        return redirect()->route('platform.tables.create');
    }
} 