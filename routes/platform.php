<?php

declare(strict_types=1);

use App\Orchid\Screens\Configuration\ConfigurationScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserCreateScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use App\Orchid\Screens\Restaurant\RestaurantListScreen;
use App\Orchid\Screens\Restaurant\RestaurantEditScreen;
use App\Orchid\Screens\RestaurantType\RestaurantTypeListScreen;
use App\Orchid\Screens\RestaurantType\RestaurantTypeEditScreen;
use App\Orchid\Screens\Amenity\AmenityListScreen;
use App\Orchid\Screens\Amenity\AmenityEditScreen;
use App\Orchid\Screens\Review\ReviewListScreen;
use App\Orchid\Screens\Review\ReviewEditScreen;
use App\Orchid\Screens\Reservation\ReservationListScreen;
use App\Orchid\Screens\Reservation\ReservationEditScreen;
use App\Orchid\Screens\Restaurant\RestaurantProfileScreen;
use App\Orchid\Screens\Customer\CustomerListScreen;
use App\Orchid\Screens\Customer\CustomerEditScreen;
use App\Orchid\Screens\Restaurant\RestaurantDetailScreen;
use App\Orchid\Screens\Table\TableListScreen;
use App\Orchid\Screens\Table\TableEditScreen;
use App\Orchid\Screens\Table\TableGridViewScreen;
use App\Orchid\Screens\Restaurant\RestaurantStatsScreen;
use App\Orchid\Screens\AdminStatsScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Users > Create
Route::screen('users/create', UserCreateScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users > Edit
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn (Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push(__('Edit'), route('platform.systems.users.edit', $user)));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn (Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push($role->name, route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));

//Configuration
Route::screen('/configuration', ConfigurationScreen::class)
        ->name('platform.configurations')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('platform.index')
            ->push(__('Configuration')));

// Restaurant Management
Route::screen('/restaurant', RestaurantListScreen::class)
    ->name('platform.restaurant.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Restaurants')));

// Restaurant Statistics
Route::screen('/restaurant/stats', RestaurantStatsScreen::class)
    ->name('platform.restaurant.stats')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.restaurant.list')
        ->push('Thống kê nhà hàng'));

        // Restaurant Profile
Route::screen('/restaurant/profile', RestaurantProfileScreen::class)
->name('platform.restaurant.profile')
->breadcrumbs(fn (Trail $trail) => $trail
    ->parent('platform.index')
    ->push(__('Restaurant Profile')));

Route::screen('/restaurant/{restaurant}', RestaurantDetailScreen::class)
    ->name('platform.restaurant.detail')
    ->breadcrumbs(fn (Trail $trail, $restaurant) => $trail
        ->parent('platform.restaurant.list')
        ->push($restaurant->name));

// Restaurant Type Management
Route::screen('/restaurant-type', RestaurantTypeListScreen::class)
    ->name('platform.restaurant-type.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Restaurant Types')));

// Amenity Management
Route::screen('/amenity', AmenityListScreen::class)
    ->name('platform.amenity.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Amenities')));

// Review Management
Route::screen('/review', ReviewListScreen::class)
    ->name('platform.review.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Reviews')));

// Reservation Management
Route::screen('/reservation', ReservationListScreen::class)
    ->name('platform.reservation.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Reservations')));

Route::screen('/reservation/{reservation}/edit', ReservationEditScreen::class)
    ->name('platform.reservation.edit')
    ->breadcrumbs(fn (Trail $trail, $reservation) => $trail
        ->parent('platform.reservation.list')
        ->push($reservation->exists ? __('Edit Reservation') : __('Create Reservation')));

// Customer Management
Route::screen('/customer', CustomerListScreen::class)
    ->name('platform.customer.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Customers')));

Route::screen('/customer/{customer}/edit', CustomerEditScreen::class)
    ->name('platform.customer.edit')
    ->breadcrumbs(fn (Trail $trail, $customer) => $trail
        ->parent('platform.customer.list')
        ->push($customer->exists ? $customer->full_name : 'Thêm khách hàng mới'));

// Table Management Routes
Route::screen('tables', TableGridViewScreen::class)
    ->name('platform.tables')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Tables'), route('platform.tables')));

Route::screen('tables/create', TableEditScreen::class)
    ->name('platform.tables.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.tables')
        ->push(__('Create Table')));

Route::screen('tables/{table}/edit', TableEditScreen::class)
    ->name('platform.tables.edit')
    ->breadcrumbs(fn (Trail $trail, $table) => $trail
        ->parent('platform.tables')
        ->push(__('Edit Table')));

// Admin Statistics
Route::screen('/stats', AdminStatsScreen::class)
    ->name('platform.stats')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Thống kê hệ thống'));