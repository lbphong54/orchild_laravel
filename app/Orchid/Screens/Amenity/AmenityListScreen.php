<?php

namespace App\Orchid\Screens\Amenity;

use App\Models\Amenity;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;

class AmenityListScreen extends Screen
{
    public function name(): ?string
    {
        return 'Quản lý tiện ích';
    }

    public function query(): array
    {
        return [
            'amenities' => Amenity::orderBy('id', 'desc')->paginate()
        ];
    }

    public function commandBar(): array
    {
        return [
            ModalToggle::make('Tạo mới')
                ->modal('createAmenityModal')
                ->icon('plus')
                ->method('create'),
        ];
    }

    public function layout(): array
    {
        return [
            Layout::table('amenities', [
                TD::make('id', 'ID')
                    ->sort()
                    ->filter(),
                TD::make('name', 'Tên tiện ích')
                    ->sort()
                    ->filter(),
                TD::make('created_at', 'Ngày tạo')
                    ->sort()
                    ->render(fn(Amenity $amenity) => $amenity->created_at->format('d/m/Y H:i')),
                TD::make('actions', 'Thao tác')
                    ->align(TD::ALIGN_CENTER)
                    ->width('100px')
                    ->render(fn (Amenity $amenity) => ModalToggle::make('')
                        ->modal('editAmenityModal')
                        ->method('update')
                        ->modalTitle('Chỉnh sửa tiện ích')
                        ->icon('eye')
                        ->asyncParameters([
                            'amenity' => $amenity->id
                        ])),
            ]),

            Layout::modal('createAmenityModal', [
                Layout::rows([
                    \Orchid\Screen\Fields\Input::make('amenity.name')
                        ->title('Tên tiện ích')
                        ->required(),
                ]),
            ])
                ->title('Tạo tiện ích mới')
                ->applyButton('Tạo mới'),

            Layout::modal('editAmenityModal', [
                Layout::rows([
                    \Orchid\Screen\Fields\Input::make('amenity.name')
                        ->title('Tên tiện ích')
                        ->required(),
                ]),
            ])
                ->async('asyncGetAmenity')
                ->applyButton('Cập nhật'),
        ];
    }

    public function asyncGetAmenity(Amenity $amenity): array
    {
        return [
            'amenity' => $amenity,
        ];
    }

    public function create(Request $request)
    {
        $request->validate([
            'amenity.name' => 'required',
        ]);

        Amenity::create($request->get('amenity'));

        return redirect()->route('platform.amenity.list');
    }

    public function update(Request $request, Amenity $amenity)
    {
        $request->validate([
            'amenity.name' => 'required',
        ]);

        $amenity->update($request->get('amenity'));

        return redirect()->route('platform.amenity.list');
    }
}