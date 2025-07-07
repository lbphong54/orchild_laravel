<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Password;
use Orchid\Screen\Layouts\Rows;

class UserPasswordLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Password::make('password')
                ->placeholder('Nhập mật khẩu')
                ->title('Mật khẩu'),

            Password::make('password_confirmation')
                ->placeholder('Xác nhận mật khẩu')
                ->title('Xác nhận mật khẩu'),
        ];
    }
}
