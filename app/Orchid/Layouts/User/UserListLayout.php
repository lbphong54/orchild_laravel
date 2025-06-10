<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Orchid\Platform\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class UserListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'users';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('name', __('Name'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn(User $user) => Link::make($user->name)
                    ->route('platform.systems.users.edit', $user)),

            TD::make('email', __('Email'))
                ->sort()
                ->cantHide()
                ->filter(Input::make()),
            TD::make('roles', __('Role'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn(User $user) => $user->roles->pluck('name')->implode(', ')),
            TD::make('created_at', __('Created'))
                ->sort()
                ->render(fn(User $user) => $user->created_at->toDateTimeString()),

            TD::make('updated_at', __('Last edit'))
                ->sort()
                ->render(fn(User $user) => $user->updated_at->toDateTimeString()),
        ];
    }
}
