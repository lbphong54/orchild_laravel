<?php

namespace App\Orchid\Screens\Table;

use App\Models\Table;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;

class TableEditScreen extends Screen
{
    public $table;

    public function name(): ?string
    {
        return $this->table->exists ? 'Chỉnh sửa bàn' : 'Thêm bàn mới';
    }

    public function description(): ?string
    {
        return 'Thông tin chi tiết về bàn';
    }

    public function query(Table $table): array
    {
        return [
            'table' => $table
        ];
    }

    public function commandBar(): array
    {
        return [
            Button::make('Lưu')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->table->exists),

            Button::make('Cập nhật')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->table->exists),

            Button::make('Xóa')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->table->exists),
        ];
    }

    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('table.name')
                    ->title('Tên bàn')
                    ->placeholder('Nhập tên bàn')
                    ->help('Tên hiển thị của bàn')
                    ->required(),

                Input::make('table.min_capacity')
                    ->type('number')
                    ->title('Số người tối thiểu')
                    ->placeholder('Nhập số người tối thiểu')
                    ->help('Số lượng người tối thiểu có thể ngồi')
                    ->required(),

                Input::make('table.max_capacity')
                    ->type('number')
                    ->title('Số người tối đa')
                    ->placeholder('Nhập số người tối đa')
                    ->help('Số lượng người tối đa có thể ngồi')
                    ->required(),

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
        ];
    }

    public function createOrUpdate(Table $table, Request $request)
    {
        $data = $request->get('table');
        $data['restaurant_id'] = auth()->user()->restaurant_id;

        $table->fill($data)->save();

        return redirect()->route('platform.tables');
    }

    public function remove(Table $table)
    {
        $table->delete();

        return redirect()->route('platform.tables');
    }
} 