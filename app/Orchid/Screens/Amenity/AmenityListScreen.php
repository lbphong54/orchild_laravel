<?php

namespace App\Orchid\Screens\Amenity;

use App\Models\Amenity;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;

class AmenityListScreen extends Screen
{
    public function name(): ?string
    {
        return 'Quản lý tiện ích';
    }

    public function query(): array
    {
        return [
            'amenities' => Amenity::filters()->defaultSort('id', 'desc')->paginate()
        ];
    }

    public function commandBar(): array
    {
        return [
            Link::make('Thêm mới')
                ->icon('plus')
                ->route('platform.amenity.edit')
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
                    ->filter()
                    ->render(fn (Amenity $amenity) => Link::make($amenity->name)
                        ->route('platform.amenity.edit', $amenity)),
                TD::make('description', 'Mô tả')
                    ->sort()
                    ->filter(),
                TD::make('created_at', 'Ngày tạo')
                    ->sort()
                    ->render(fn (Amenity $amenity) => $amenity->created_at->format('d/m/Y H:i')),
            ])
        ];
    }
} 