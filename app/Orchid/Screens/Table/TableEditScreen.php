<?php

namespace App\Orchid\Screens\Table;

use App\Models\Restaurant;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Group;
use Orchid\Support\Facades\Toast;

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

    public function query(RestaurantTable $table): array
    {
        return [
            'table' => $table
        ];
    }

    public function commandBar(): array
    {
        return [
            Button::make('Lưu')
                ->icon('check')
                ->method('createOrUpdate')
                ->class('btn btn-success')
                ->canSee(!$this->table->exists),

            Button::make('Cập nhật')
                ->icon('note')
                ->method('createOrUpdate')
                ->class('btn btn-primary')
                ->canSee($this->table->exists),

            Button::make('Xóa')
                ->icon('trash')
                ->method('remove')
                ->class('btn btn-danger')
                ->canSee($this->table->exists),
        ];
    }

    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('table.name')
                    ->title('Tên bàn')
                    ->placeholder('Nhập tên bàn (VD: Bàn 1, VIP 1)')
                    ->help('Tên hiển thị của bàn')
                    ->required()
                    ->maxlength(50),

                Group::make([
                    Input::make('table.min_capacity')
                        ->type('number')
                        ->title('Số người tối thiểu')
                        ->placeholder('2')
                        ->help('Số lượng người tối thiểu có thể ngồi')
                        ->required()
                        ->min(1)
                        ->max(20),

                    Input::make('table.max_capacity')
                        ->type('number')
                        ->title('Số người tối đa')
                        ->placeholder('4')
                        ->help('Số lượng người tối đa có thể ngồi')
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
            ])
        ];
    }

    public function createOrUpdate(RestaurantTable $table, Request $request)
    {
        $request->validate([
            'table.name' => 'required|string|max:50',
            'table.min_capacity' => 'required|integer|min:1|max:20',
            'table.max_capacity' => 'required|integer|min:1|max:50',
            'table.status' => 'required|in:available,occupied,reserved',
        ], [
            'table.name.required' => 'Tên bàn là bắt buộc',
            'table.name.max' => 'Tên bàn không được quá 50 ký tự',
            'table.min_capacity.required' => 'Số người tối thiểu là bắt buộc',
            'table.min_capacity.min' => 'Số người tối thiểu phải lớn hơn 0',
            'table.min_capacity.max' => 'Số người tối thiểu không được quá 20',
            'table.max_capacity.required' => 'Số người tối đa là bắt buộc',
            'table.max_capacity.min' => 'Số người tối đa phải lớn hơn 0',
            'table.max_capacity.max' => 'Số người tối đa không được quá 50',
            'table.status.required' => 'Trạng thái là bắt buộc',
            'table.status.in' => 'Trạng thái không hợp lệ',
        ]);

        // Kiểm tra min_capacity không được lớn hơn max_capacity
        if ($request->input('table.min_capacity') > $request->input('table.max_capacity')) {
            Toast::error('Số người tối thiểu không được lớn hơn số người tối đa');
            return back();
        }

        $data = $request->get('table');
        $data['restaurant_id'] = Restaurant::where('user_id', Auth::user()->id)->first()->id;

        $table->fill($data)->save();

        $message = $table->wasRecentlyCreated ? 'Bàn đã được tạo thành công!' : 'Bàn đã được cập nhật thành công!';
        Toast::info($message);

        return redirect()->route('platform.tables');
    }

    public function remove(RestaurantTable $table)
    {
        // Kiểm tra xem bàn có đang được sử dụng không
        if ($table->reservations()->exists()) {
            Toast::error('Không thể xóa bàn đang có đặt chỗ!');
            return back();
        }

        $table->delete();
        Toast::info('Bàn đã được xóa thành công!');

        return redirect()->route('platform.tables');
    }
} 