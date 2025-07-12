<?php

namespace App\Orchid\Screens\Review;

use App\Models\Restaurant;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\Input;

class ReviewListScreen extends Screen
{
    public function name(): ?string
    {
        return 'Quản lý đánh giá';
    }

    public function query(): array
    {
        if (Auth::user()->roles[0]->slug == 'admin') {
            return [
                'reviews' => Review::with(['restaurant', 'customer'])
                    ->orderBy('id', 'desc')
                    ->paginate()
            ];
        } else {
            $restauranId = Restaurant::query()->where('user_id', Auth::user()->id)->first()?->id;
            if ($restauranId) {
                return [
                    'reviews' => Review::with(['restaurant', 'customer'])
                        ->where('restaurant_id', $restauranId)
                        ->orderBy('id', 'desc')
                        ->paginate()
                ];
            }
            return [
                'reviews' => []
            ];
        }
        
    }

    public function layout(): array
    {
        return [
            Layout::table('reviews', [
                TD::make('id', 'ID')
                    ->sort()
                    ->filter(Input::make()),

                TD::make('restaurant.name', 'Nhà hàng')
                    ->sort()
                    ->filter(Input::make())
                    ->render(fn(Review $review) => Link::make($review->restaurant->name)
                        ->route('platform.restaurant.detail', $review->restaurant)),

                TD::make('customer.full_name', 'Khách hàng')
                    ->sort()
                    ->filter(Input::make()),

                TD::make('rating', 'Đánh giá')
                    ->sort()
                    ->filter(Input::make())
                    ->render(fn(Review $review) => str_repeat('⭐', $review->rating)),

                TD::make('comment', 'Nhận xét')
                    ->sort()
                    ->filter(Input::make())
                    ->width('300px'),

                TD::make('created_at', 'Ngày tạo')
                    ->sort()
                    ->render(fn(Review $review) => $review->created_at->format('d/m/Y H:i')),
            ])
        ];
    }
}
