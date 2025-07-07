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
            Menu::make('Quản lý nhà hàng & đặt bàn')
                ->icon('bs.building')
                ->title('Quản lý nhà hàng & đặt bàn')
                ->list([
                    Menu::make('Nhà hàng')
                        ->icon('bs.building')
                        ->list([
                            Menu::make('Danh sách nhà hàng')
                                ->icon('bs.list')
                                ->route('platform.restaurant.list'),
                            Menu::make('Thông tin nhà hàng')
                                ->icon('bs.person')
                                ->route('platform.restaurant.profile')
                                ->permission('platform.restaurant.manage'),
                            Menu::make('Loại nhà hàng')
                                ->icon('bs.tags')
                                ->route('platform.restaurant-type.list'),
                            Menu::make('Tiện ích')
                                ->icon('bs.stars')
                                ->route('platform.amenity.list'),
                        ]),
                    Menu::make('Đặt bàn')
                        ->icon('bs.calendar')
                        ->list([
                            Menu::make('Bàn')
                                ->icon('bs.table')
                                ->route('platform.tables'),
                            Menu::make('Danh sách đặt bàn')
                                ->icon('bs.list')
                                ->route('platform.reservation.list'),
                        ]),
                ])
                ->divider(),

            // Nhóm Khách hàng & Đánh giá
            Menu::make('Khách hàng')
                ->icon('bs.people')
                ->title('Khách hàng & đánh giá')
                ->list([
                    Menu::make('Danh sách khách hàng')
                        ->icon('bs.people')
                        ->route('platform.customer.list'),
                    Menu::make('Đánh giá')
                        ->icon('bs.star')
                        ->list([
                            Menu::make('Danh sách đánh giá')
                                ->icon('bs.list')
                                ->route('platform.review.list'),
                        ]),
                ])
                ->divider(),

            // Thống kê
            Menu::make('Thống kê')
                ->icon('bs.bar-chart')
                ->route('platform.stats')
                ->divider(),

            // Access Controls
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