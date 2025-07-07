<?php

namespace App\Orchid\Layouts\User;

use Orchid\Platform\Models\Role;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Password;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class UserCreateLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('user.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title('Tên')
                ->placeholder('Tên'),

            Input::make('user.email')
                ->type('email')
                ->required()
                ->title('Email')
                ->placeholder('Email'),

            Password::make('password')
                ->required()
                ->title('Mật khẩu')
                ->placeholder('Nhập mật khẩu'),

            Password::make('password_confirmation')
                ->required()
                ->title('Xác nhận mật khẩu')
                ->placeholder('Xác nhận mật khẩu'),

            Select::make('user.roles.')
                ->fromModel(Role::class, 'name')
                ->multiple()
                ->title('Vai trò')
                ->help('Chọn vai trò cho người dùng'),
        ];
    }
}