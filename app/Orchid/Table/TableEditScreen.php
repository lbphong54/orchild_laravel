<?php

namespace App\Orchid\Screens\Table;

use App\Models\Table;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;

class TableEditScreen extends Screen
{
    public $table;

    public function name(): ?string
    {
        return $this->table->exists ? 'Chỉnh sửa bàn' : 'Thêm bàn mới';
    }

    public function query(Table $table): array
    {
        return [
            'table' => $table,
            'restaurants' => Restaurant::all(),
        ];
    }

    public function commandBar(): array
    {
        return [
            Button::make('Lưu')->icon('pencil')->method('createOrUpdate')->canSee(!$this->table->exists),
            Button::make('Cập nhật')->icon('note')->method('createOrUpdate')->canSee($this->table->exists),
            Button::make('Xóa')->icon('trash')->method('remove')->canSee($this->table->exists),
        ];
    }

    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('table.name')->title('Tên bàn')->required(),
                Input::make('table.capacity')->title('Sức chứa')->type('number')->required(),
                Select::make('table.restaurant_id')->fromModel(Restaurant::class, 'name')->title('Nhà hàng')->required(),
                Input::make('table.status')->title('Trạng thái')->placeholder('available, reserved, inactive'),
                TextArea::make('table.description')->title('Mô tả')->rows(3),
            ])
        ];
    }

    public function createOrUpdate(Table $table, Request $request)
    {
        $table->fill($request->get('table'))->save();
        return redirect()->route('platform.table.list');
    }

    public function remove(Table $table)
    {
        $table->delete();
        return redirect()->route('platform.table.list');
    }
}