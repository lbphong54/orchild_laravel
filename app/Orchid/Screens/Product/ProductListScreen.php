<?php

namespace App\Orchid\Screens\Product;

use App\Models\Product;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use App\Http\Requests\CreateProductRequest;
use App\Orchid\Layouts\Product\ProductFillter;
use App\Orchid\Layouts\Product\ProductListLayout;

class ProductListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'products' => Product::with('partner')
                    ->filters(ProductFillter::class)
                    ->defaultSort('id', 'desc')
                    ->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Product List';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Add Product')
                ->modal('productModal')
                ->method('create')
                ->icon('plus'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            ProductFillter::class,
            ProductListLayout::class,

            Layout::modal('productModal', Layout::rows([
                Input::make('product.name')
                    ->title('Name')
                    ->placeholder('Enter product name')
                    ->help('The name of the product to be created.'),
                Input::make('product.price')
                    ->title('Price')
                    ->placeholder('Enter product price')
                    ->help('The price of the product to be created.'),
                Input::make('product.description')
                    ->title('Description')
                    ->placeholder('Enter product description')
                    ->help('The description of the product to be created.'),
            ]))
                ->title('Create Product')
                ->applyButton('Add Product')
        ];
    }

    /**
     * Create new product.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(CreateProductRequest $request): void   
    {
        $validated = $request->validated();
        Product::create($validated['product']);
    }
}
