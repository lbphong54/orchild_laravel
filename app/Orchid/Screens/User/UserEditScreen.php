<?php

declare(strict_types=1);

namespace App\Orchid\Screens\User;

use App\Orchid\Layouts\Role\RolePermissionLayout;
use App\Orchid\Layouts\User\UserEditLayout;
use App\Orchid\Layouts\User\UserPasswordLayout;
use App\Orchid\Layouts\User\UserRoleLayout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Orchid\Access\Impersonation;
use App\Models\User;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class UserEditScreen extends Screen
{
    /**
     * @var User
     */
    public $user;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(User $user): iterable
    {
        $user->load(['roles']);

        return [
            'user'       => $user,
            'permission' => $user->getStatusPermission(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return $this->user->exists ? 'Chỉnh sửa người dùng' : 'Tạo người dùng';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Chi tiết và quyền hạn của người dùng';
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
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Đăng nhập với tư cách người dùng')
                ->icon('login')
                ->confirm('Bạn có thể quay lại trạng thái ban đầu bằng cách đăng xuất.')
                ->method('loginAs')
                ->canSee($this->user->exists && \request()->user()->id !== $this->user->id),

            Button::make('Xóa')
                ->icon('trash')
                ->confirm('Khi tài khoản bị xóa, tất cả dữ liệu liên quan sẽ bị xóa vĩnh viễn. Vui lòng tải về các dữ liệu bạn muốn giữ lại trước khi xóa.')
                ->method('remove')
                ->canSee($this->user->exists),

            Button::make('Lưu')
                ->icon('check')
                ->method('save'),
        ];
    }

    /**
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            Layout::block(UserEditLayout::class)
                ->title('Thông tin cá nhân')
                ->description('Cập nhật thông tin cá nhân và email.')
                ->commands(
                    Button::make('Lưu')
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->method('save')
                ),

            Layout::block(UserPasswordLayout::class)
                ->title('Cập nhật mật khẩu')
                ->description('Hãy sử dụng mật khẩu dài và ngẫu nhiên để bảo mật tài khoản.')
                ->commands(
                    Button::make('Cập nhật mật khẩu')
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->method('changePassword')
                ),

            Layout::block(UserRoleLayout::class)
                ->title('Vai trò')
                ->description('Vai trò xác định các quyền mà người dùng được phép thực hiện.')
                ->commands(
                    Button::make('Lưu vai trò')
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->method('save')
                ),
        ];
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(User $user, Request $request)
    {
        $request->validate([
            'user.email' => [
                'required',
                Rule::unique(User::class, 'email')->ignore($user),
            ],
        ]);

        $permissions = collect($request->get('permissions'))
            ->map(fn ($value, $key) => [base64_decode($key) => $value])
            ->collapse()
            ->toArray();

        $userData = $request->get('user');
        if ($user->exists && (!isset($userData['password']) || empty($userData['password']))) {
            unset($userData['password']);
        } elseif (isset($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        }

        $user
            ->fill($userData)
            ->replaceRoles($request->input('user.roles'))
            ->fill([
                'permissions' => $permissions,
            ]);

        $user->save();

        Toast::info('Đã lưu người dùng.');

        return redirect()->route('platform.systems.users');
    }

    /**
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(User $user)
    {
        $user->delete();

        Toast::info('Đã xóa người dùng');

        return redirect()->route('platform.systems.users');
    }

    /**
     * @param User    $user
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(User $user, Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->save();
        Toast::info('Đã lưu người dùng.');
    }
}
