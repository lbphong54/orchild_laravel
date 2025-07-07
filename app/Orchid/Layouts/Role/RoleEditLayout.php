<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Role;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class RoleEditLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('role.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title('Tên')
                ->placeholder('Tên')
                ->help('Tên hiển thị vai trò'),

            Input::make('role.slug')
                ->type('text')
                ->max(255)
                ->required()
                ->title('Định danh')
                ->placeholder('Định danh')
                ->help('Tên thực tế trong hệ thống'),
        ];
    }
}
