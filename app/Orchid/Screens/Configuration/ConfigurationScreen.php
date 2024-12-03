<?php

namespace App\Orchid\Screens\Configuration;

use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use App\Orchid\Fields\CustomTabs;
use App\Orchid\Layouts\Configuration\CategoryLayout;
use App\Orchid\Layouts\Configuration\ColorLayout;
use App\Orchid\Layouts\Configuration\SizeLayout;
use Illuminate\Support\Facades\Log;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;

class ConfigurationScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'categories' => Category::all(),
            'sizes'=> Size::all(),
            'colors'=> Color::all(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('Cấu hình');
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            DropDown::make('')
                ->icon('bs.three-dots-vertical')
                ->list([
                    ModalToggle::make('Add Category')->modal('categoryModal')->method('createCategory'),
                    ModalToggle::make('Add Color')->modal('colorModal')->method('createColor'),
                    ModalToggle::make('Add Size')->modal('sizeModal')->method('createSize'),
                ]),
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
            Layout::tabs([
                __('Category') => [
                    CategoryLayout::class
                ],
                __('Color') => [
                    ColorLayout::class
                ],
                __('Size') => [
                    SizeLayout::class
                ],
            ]),

            Layout::modal('categoryModal', Layout::rows([
                Input::make('name')
                    ->title('Name')
                    ->placeholder('Enter name')
                    ->help('The name to be created.')
            ]))
            ->title('Create category')
            ->applyButton('Add category'),

            Layout::modal('colorModal', Layout::rows([
                Input::make('name')
                    ->title('Name')
                    ->required()
                    ->placeholder('Enter name'),
                Input::make('color')
                    ->type('color')
                    ->title('Color Picker')
                    ->horizontal(),
            ]))
            ->title('Create color')
            ->applyButton('Add color'),

            Layout::modal('sizeModal', Layout::rows([
                Input::make('name')
                    ->title('Name')
                    ->placeholder('Enter name'),
            ]))
            ->title('Create size')
            ->applyButton('Add size'),
        ];
    }

    public function createColor(Request $request)
    {
        $name = $request->input('name');
        $color = $request->input('color');

        Color::create([
            'name'=> $name,
            'color'=> $color
        ]);
    }
}
