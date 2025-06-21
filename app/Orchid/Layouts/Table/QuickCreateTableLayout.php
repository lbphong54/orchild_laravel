<?php

namespace App\Orchid\Layouts\Table;

use Orchid\Screen\Layout;
use Orchid\Screen\Repository;
use Orchid\Support\Facades\Layout as LayoutFacade;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Group;

class QuickCreateTableLayout extends Layout
{
    public function build(Repository $repository)
    {
        return LayoutFacade::rows([
            Group::make([
                Input::make('table.name')
                    ->title('Tên bàn')
                    ->placeholder('Bàn 1')
                    ->required(),

                Input::make('table.min_capacity')
                    ->type('number')
                    ->title('Số người tối thiểu')
                    ->placeholder('2')
                    ->required()
                    ->min(1)
                    ->max(20),

                Input::make('table.max_capacity')
                    ->type('number')
                    ->title('Số người tối đa')
                    ->placeholder('4')
                    ->required()
                    ->min(1)
                    ->max(50),
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
        ]);
    }
} 