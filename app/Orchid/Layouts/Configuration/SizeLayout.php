<?php

namespace App\Orchid\Layouts\Configuration;

use App\Models\Size;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SizeLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'sizes';

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
            TD::make()
                ->align(TD::ALIGN_RIGHT)
                ->render(function (Size $size) {
                    if ($size->isDeletable) {
                        return Button::make(__('Delete'))
                            ->icon('trash')
                            ->method('deleteSize', ['id' => $size->id]);
                    }
                    return null;
                }),
        ];
    }
}
