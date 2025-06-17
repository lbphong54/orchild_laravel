<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Illuminate\Support\Facades\Auth;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        return [
            Menu::make('Quản lý nhà hàng')
                ->icon('bs.building')
                ->title('Nhà hàng')
                ->list([
                    Menu::make('Danh sách nhà hàng')
                        ->icon('bs.list')
                        ->route('platform.restaurant.list'),
                    Menu::make('Thông tin nhà hàng')
                        ->icon('bs.person')
                        ->route('platform.restaurant.profile')
                        // ->canSee(Auth::check() && Auth::user()->restaurant_id)
                        ->permission('platform.restaurant.manage'),
                    Menu::make('Loại nhà hàng')
                        ->icon('bs.tags')
                        ->route('platform.restaurant-type.list'),
                    Menu::make('Tiện ích')
                        ->icon('bs.stars')
                        ->route('platform.amenity.list'),
                ]),

            Menu::make('Quản lý đặt bàn')
                ->icon('bs.calendar')
                ->title('Đặt bàn')
                ->list([
                    Menu::make('Danh sách đặt bàn')
                        ->icon('bs.list')
                        ->route('platform.reservation.list'),
                ]),

            Menu::make('Danh sách khách hàng')
                ->icon('bs.people')
                ->title('Khách hàng')
                ->route('platform.customer.list'),


            Menu::make('Quản lý đánh giá')
                ->icon('bs.star')
                ->title('Đánh giá')
                ->list([
                    Menu::make('Danh sách đánh giá')
                        ->icon('bs.list')
                        ->route('platform.review.list'),
                ]),

            Menu::make(__('Cấu hình'))
                ->icon('bs.gear')
                ->route('platform.configurations'),

            Menu::make(__('Users'))
                ->icon('bs.people')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Access Controls')),

            Menu::make(__('Roles'))
                ->icon('bs.shield')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')
                ->divider(),
        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
            ItemPermission::group(__('Restaurant'))
                ->addPermission('platform.restaurant.manage', __('Manage Restaurants'))
                ->addPermission('platform.restaurant-type.manage', __('Manage Restaurant Types'))
                ->addPermission('platform.amenity.manage', __('Manage Amenities'))
                ->addPermission('platform.reservation.manage', __('Manage Reservations'))
                ->addPermission('platform.review.manage', __('Manage Reviews'))
                ->addPermission('platform.customer.manage', __('Manage Customers')),
        ];
    }
}
