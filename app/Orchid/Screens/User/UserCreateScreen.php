<?php

namespace App\Orchid\Screens\User;

use App\Orchid\Layouts\User\UserCreateLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Orchid\Platform\Models\User;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class UserCreateScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'user' => new User(),
        ];
    }

    public function name(): ?string
    {
        return 'Tạo người dùng';
    }

    public function description(): ?string
    {
        return 'Tạo tài khoản người dùng mới';
    }

    public function permission(): ?iterable
    {
        return [
            'platform.systems.users',
        ];
    }

    public function commandBar(): iterable
    {
        return [];
    }

    public function layout(): iterable
    {
        return [
            Layout::block(UserCreateLayout::class)
                ->commands(
                    Button::make('Tạo')
                        ->icon('check')
                        ->method('create')
                ),
        ];
    }

    public function create(Request $request)
    {
        $request->validate([
            'user.email' => [
                'required',
                Rule::unique(User::class, 'email'),
            ],
            'user.name' => 'required',
            'password' => 'required|confirmed|min:8',
        ], [
            'user.email.required' => 'Vui lòng nhập email.',
            'user.email.unique' => 'Email đã tồn tại.',
            'user.name.required' => 'Vui lòng nhập tên.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
        ]);

        $user = new User();
        $user->fill($request->get('user'));
        $user->password = Hash::make($request->get('password'));
        $user->save();

        $user->replaceRoles($request->input('user.roles'));

        Toast::info('Người dùng đã được tạo thành công.');

        return redirect()->route('platform.systems.users');
    }
}
