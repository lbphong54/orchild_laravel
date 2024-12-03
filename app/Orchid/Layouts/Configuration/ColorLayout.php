<?php

namespace App\Orchid\Layouts\Configuration;

use App\Models\Color;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class ColorLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'colors';

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
                ->cantHide()
                ->filter(Input::make()),
            TD::make('color', __('Color'))
                ->render(function (Color $color) {
                    return "<div style='width: 50px; height: 20px; background-color: {$color->color}; border: 1px solid #ccc;'></div>";
                }),
            TD::make('', __(''))
                ->align(TD::ALIGN_RIGHT)
                ->cantHide()
                ->render(function (Color $color) {
                    if ($color->isDeletable()) {
                        return Button::make(__('Delete'))
                            ->icon('trash')
                            ->method('deleteColor', ['id' => $color->id]);
                    }
                    return null;
                }),
        ];
    }
}
