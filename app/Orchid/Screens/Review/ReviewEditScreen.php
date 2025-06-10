<?php

namespace App\Orchid\Screens\Review;

use App\Models\Review;
use App\Models\Restaurant;
use App\Models\Customer;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;

class ReviewEditScreen extends Screen
{
    public $review;

    public function name(): ?string
    {
        return $this->review->exists ? 'Chỉnh sửa đánh giá' : 'Thêm đánh giá mới';
    }

    public function query(Review $review): array
    {
        return [
            'review' => $review,
            'restaurants' => Restaurant::all(),
            'customers' => Customer::all(),
        ];
    }

    public function commandBar(): array
    {
        return [
            Button::make('Lưu')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->review->exists),

            Button::make('Cập nhật')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->review->exists),

            Button::make('Xóa')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->review->exists),
        ];
    }

    public function layout(): array
    {
        return [
            Layout::rows([
                Select::make('review.restaurant_id')
                    ->fromModel(Restaurant::class, 'name')
                    ->title('Nhà hàng')
                    ->required(),

                Select::make('review.customer_id')
                    ->fromModel(Customer::class, 'name')
                    ->title('Khách hàng')
                    ->required(),

                Input::make('review.rating')
                    ->title('Đánh giá')
                    ->type('number')
                    ->min(1)
                    ->max(5)
                    ->required(),

                TextArea::make('review.comment')
                    ->title('Nhận xét')
                    ->placeholder('Nhập nhận xét')
                    ->rows(3),
            ])
        ];
    }

    public function createOrUpdate(Review $review, Request $request)
    {
        $review->fill($request->get('review'))->save();

        return redirect()->route('platform.review.list');
    }

    public function remove(Review $review)
    {
        $review->delete();

        return redirect()->route('platform.review.list');
    }
} 