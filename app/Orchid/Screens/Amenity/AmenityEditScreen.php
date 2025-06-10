<?php

namespace App\Orchid\Screens\Amenity;

use App\Models\Amenity;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;

class AmenityEditScreen extends Screen
{
    public $amenity;

    public function name(): ?string
    {
        return $this->amenity->exists ? 'Chỉnh sửa tiện ích' : 'Thêm tiện ích mới';
    }

    public function query(Amenity $amenity): array
    {
        return [
            'amenity' => $amenity
        ];
    }

    public function commandBar(): array
    {
        return [
            Button::make('Lưu')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->amenity->exists),

            Button::make('Cập nhật')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->amenity->exists),

            Button::make('Xóa')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->amenity->exists),
        ];
    }

    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('amenity.name')
                    ->title('Tên tiện ích')
                    ->placeholder('Nhập tên tiện ích')
                    ->required(),

                TextArea::make('amenity.description')
                    ->title('Mô tả')
                    ->placeholder('Nhập mô tả')
                    ->rows(3),
            ])
        ];
    }

    public function createOrUpdate(Amenity $amenity, Request $request)
    {
        $amenity->fill($request->get('amenity'))->save();

        return redirect()->route('platform.amenity.list');
    }

    public function remove(Amenity $amenity)
    {
        $amenity->delete();

        return redirect()->route('platform.amenity.list');
    }
} 