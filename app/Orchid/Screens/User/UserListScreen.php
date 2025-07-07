<?php

declare(strict_types=1);

namespace App\Orchid\Screens\User;

use App\Orchid\Layouts\User\UserEditLayout;
use App\Orchid\Layouts\User\UserFiltersLayout;
use App\Orchid\Layouts\User\UserListLayout;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Orchid\Layouts\User\UserCreateLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Actions\ModalToggle;
use Illuminate\Support\Facades\Hash;

class UserListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'users' => User::with('roles')
                ->filters(UserFiltersLayout::class)
                ->defaultSort('id', 'desc')
                ->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Quản lý người dùng';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Danh sách toàn bộ người dùng trong hệ thống';
    }

    public function permission(): ?iterable
    {
        return [
            'platform.systems.users',
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Thêm mới')
                ->modal('createUserModal')
                ->method('createUser')
                ->icon('plus'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return string[]|\Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            UserFiltersLayout::class,
            UserListLayout::class,

            Layout::modal('editUserModal', UserEditLayout::class)
                ->deferred('loadUserOnOpenModal'),

            Layout::modal('createUserModal', UserCreateLayout::class)
                ->title('Tạo người dùng')
                ->applyButton('Tạo người dùng')
        ];
    }

    /**
     * Loads user data when opening the modal window.
     *
     * @return array
     */
    public function loadUserOnOpenModal(User $user): iterable
    {
        return [
            'user' => $user,
        ];
    }

    public function saveUser(Request $request, User $user): void
    {
        $request->validate([
            'user.email' => [
                'required',
                Rule::unique(User::class, 'email')->ignore($user),
            ],
        ]);

        $user->fill($request->input('user'))->save();

        Toast::info('Đã lưu người dùng.');
    }

    public function remove(User $user)
    {
        $user->delete();

        Toast::info('Đã xóa người dùng');
    }

    public function asyncCreateUser(): array
    {
        return [
            'user' => new User(),
        ];
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'user.email' => [
                'required',
                Rule::unique(User::class, 'email'),
            ],
            'user.name' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = new User();
        $user->fill($request->get('user'));
        $user->password = Hash::make($request->get('password'));
        $user->save();
        
        $user->roles()->sync($request->input('user.roles'));

        Toast::info('Tạo người dùng thành công.');
    }
}
