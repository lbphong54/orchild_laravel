<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Product;

use App\Models\Product;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ProductListLayout extends Table
{
    public $target = 'products';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('id', __('ID'))
                ->sort()
                ->filter(Input::make()),
            TD::make('name', __('Name'))
                ->sort()
                ->cantHide()
                ->filter(Input::make()),
            TD::make('price', __('Price'))
                ->sort()
                ->align(TD::ALIGN_RIGHT)
                ->cantHide()
                ->filter(Input::make()),
            TD::make('stock', __('Stock'))
                ->sort()
                ->align(TD::ALIGN_RIGHT)
                ->cantHide()
                ->filter(Input::make()),
            TD::make('created_at', __('Created'))
                ->usingComponent(DateTimeSplit::class)
                ->align(TD::ALIGN_RIGHT)
                ->defaultHidden()
                ->sort(),

            TD::make('updated_at', __('Last edit'))
                ->usingComponent(DateTimeSplit::class)
                ->align(TD::ALIGN_RIGHT)
                ->defaultHidden()
                ->sort(),
            TD::make(__(''))
                ->align(TD::ALIGN_RIGHT)  
                ->render(fn (Product $product) => Link::make()
                    ->icon('eye')
                    ->route('platform.products.detail', [$product])),            
        ];  
    }
}