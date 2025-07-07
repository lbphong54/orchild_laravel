<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Password;
use Orchid\Screen\Layouts\Rows;

class ProfilePasswordLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Password::make('old_password')
            ->placeholder('Nhập mật khẩu hiện tại')
            ->title('Mật khẩu hiện tại')
            ->help('Đây là mật khẩu bạn đang sử dụng.'),

            Password::make('password')
            ->placeholder('Nhập mật khẩu mới')
            ->title('Mật khẩu mới'),

            Password::make('password_confirmation')
            ->placeholder('Nhập lại mật khẩu mới')
            ->title('Xác nhận mật khẩu mới')
            ->help('Mật khẩu mạnh nên có ít nhất 15 ký tự hoặc ít nhất 8 ký tự bao gồm số và chữ thường.'),
        ];
    }
}
