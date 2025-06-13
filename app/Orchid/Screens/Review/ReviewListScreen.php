<?php

namespace App\Orchid\Screens\Review;

use App\Models\Review;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;

class ReviewListScreen extends Screen
{
    public function name(): ?string
    {
        return 'Quản lý đánh giá';
    }

    public function query(): array
    {
        return [
            'reviews' => Review::with(['restaurant', 'customer'])
                ->orderBy('id', 'desc')
                ->paginate()
        ];
    }

    public function layout(): array
    {
        return [
            Layout::table('reviews', [
                TD::make('id', 'ID')
                    ->sort()
                    ->filter(),
                TD::make('restaurant.name', 'Nhà hàng')
                    ->sort()
                    ->filter()
                    ->render(fn (Review $review) => Link::make($review->restaurant->name)
                        ->route('platform.restaurant.edit', $review->restaurant)),
                TD::make('customer.name', 'Khách hàng')
                    ->sort()
                    ->filter(),
                TD::make('rating', 'Đánh giá')
                    ->sort()
                    ->filter()
                    ->render(fn (Review $review) => str_repeat('⭐', $review->rating)),
                TD::make('comment', 'Nhận xét')
                    ->sort()
                    ->filter()
                    ->width('300px'),
                TD::make('created_at', 'Ngày tạo')
                    ->sort()
                    ->render(fn (Review $review) => $review->created_at->format('d/m/Y H:i')),
            ])
        ];
    }
} 