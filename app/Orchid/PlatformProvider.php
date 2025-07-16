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
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        return [
            // Nhóm Quản lý Nhà hàng & Đặt bàn

            Menu::make('Nhà hàng')
                ->icon('bs.building')
                ->permission('platform.restaurant.manage')
                ->list([
                    Menu::make('Danh sách nhà hàng')
                        ->icon('bs.list')
                        ->route('platform.restaurant.list')
                        ->permission('platform.restaurant.manage'),
                    Menu::make('Loại nhà hàng')
                        ->icon('bs.tags')
                        ->route('platform.restaurant-type.list')
                        ->permission('platform.restaurant-type.manage'),
                    Menu::make('Tiện ích')
                        ->icon('bs.stars')
                        ->route('platform.amenity.list')
                        ->permission('platform.amenity.manage'),
                ]),
            Menu::make('Thông tin nhà hàng')
                ->icon('bs.person')
                ->route('platform.restaurant.profile')
                ->permission('platform.restaurant.profile'),

            Menu::make('Bàn')
                ->icon('bs.table')
                ->route('platform.tables')
                ->permission('platform.tables.manage'),
            Menu::make('Danh sách đặt bàn')
                ->icon('bs.list')
                ->route('platform.reservation.list')
                ->permission('platform.reservation.manage'),
            // Nhóm Khách hàng & Đánh giá

            Menu::make('Danh sách khách hàng')
                ->icon('bs.people')
                ->route('platform.customer.list')
                ->permission('platform.customer.manage'),
            Menu::make('Danh sách đánh giá')
                ->icon('bs.list')
                ->route('platform.review.list'),


            // Thống kê
            Menu::make('Thống kê')
                ->icon('bs.bar-chart')
                ->route('platform.stats')
                ->permission('platform.stats')
                ->divider(),

            // Access Controls
            Menu::make(__('Tài khoản quản trị'))
                ->icon('bs.people')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Tài khoản quản trị')),
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
