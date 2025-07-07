<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Role;

use Orchid\Platform\Models\Role;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class RoleListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'roles';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('name', 'Tên')
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn (Role $role) => Link::make($role->name)
                    ->route('platform.systems.roles.edit', $role->id)),

            TD::make('slug', 'Định danh')
                ->sort()
                ->cantHide()
                ->filter(Input::make()),

            TD::make('created_at', 'Ngày tạo')
                ->usingComponent(DateTimeSplit::class)
                ->align(TD::ALIGN_RIGHT)
                ->defaultHidden()
                ->sort(),

            TD::make('updated_at', 'Chỉnh sửa lần cuối')
                ->usingComponent(DateTimeSplit::class)
                ->align(TD::ALIGN_RIGHT)
                ->sort(),
        ];
    }
}
