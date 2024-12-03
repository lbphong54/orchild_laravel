<?php

namespace App\Orchid\Layouts\Configuration;

use App\Models\Category;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CategoryLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'categories';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', __('Name'))
                ->sort()
                ->filter(Input::make()),
            TD::make('', __(''))
                ->align(TD::ALIGN_RIGHT)
                ->render(function (Category $category) {
                    if ($category->isDeletable()) {
                        return Button::make(__('Delete'))
                            ->icon('trash')
                            ->method('deleteCategory', ['id' => $category->id]);
                    }
                    return null;
                }),
        ];
    }
}
