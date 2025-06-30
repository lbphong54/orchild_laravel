<?php

namespace App\Orchid\Screens;

use App\Models\Restaurant;
use App\Models\Reservation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Carbon\Carbon;
use Orchid\Screen\Fields\DateTimer;
use App\Orchid\Layouts\AdminReservationChartLayout;

class AdminStatsScreen extends Screen
{
    public function name(): ?string
    {
        return 'Thống kê hệ thống';
    }

    public function query(): array
    {
        $from = request('from_date') ? Carbon::parse(request('from_date')) : Carbon::now()->subDays(29)->startOfDay();
        $to = request('to_date') ? Carbon::parse(request('to_date')) : Carbon::now()->endOfDay();

        $totalRestaurants = Restaurant::count();
        $activeRestaurants = Restaurant::where('status', 'active')->count();
        $inactiveRestaurants = Restaurant::where('status', 'inactive')->count();

        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        $totalReservationsThisMonth = Reservation::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        $completedReservations = Reservation::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('status', 'completed')->count();
        $cancelledReservations = Reservation::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('status', 'cancelled')->count();

        // Dữ liệu biểu đồ đặt bàn theo ngày
        $period = new \DatePeriod($from, new \DateInterval('P1D'), $to->copy()->addDay());
        $labels = [];
        $values = [];
        foreach ($period as $date) {
            $labels[] = $date->format('d/m');
            $values[] = Reservation::whereDate('created_at', $date)->count();
        }

        return [
            'metrics' => [
                'total_restaurants' => [
                    'value' => $totalRestaurants,
                    'title' => 'Tổng số nhà hàng',
                ],
                'active_restaurants' => [
                    'value' => $activeRestaurants,
                    'title' => 'Nhà hàng đang hoạt động',
                ],
                'inactive_restaurants' => [
                    'value' => $inactiveRestaurants,
                    'title' => 'Nhà hàng ngưng hoạt động',
                ],
                'total_reservations_month' => [
                    'value' => $totalReservationsThisMonth,
                    'title' => 'Số đơn đặt trong tháng',
                ],
                'completed_reservations' => [
                    'value' => $completedReservations,
                    'title' => 'Số đơn thành công',
                ],
                'cancelled_reservations' => [
                    'value' => $cancelledReservations,
                    'title' => 'Số đơn hủy',
                ],
            ],
            'from_date' => $from->format('Y-m-d'),
            'to_date' => $to->format('Y-m-d'),
            'reservation_chart' => [
                [
                    'name' => 'Đơn đặt bàn',
                    'values' => $values,
                    'labels' => $labels,
                ]
            ],
        ];
    }

    public function commandBar(): array
    {
        return [];
    }

    public function layout(): array
    {
        return [
            Layout::metrics([
                'Tổng số nhà hàng' => 'metrics.total_restaurants',
                'Nhà hàng đang hoạt động' => 'metrics.active_restaurants',
                'Nhà hàng ngưng hoạt động' => 'metrics.inactive_restaurants',
                'Số đơn đặt trong tháng' => 'metrics.total_reservations_month',
                'Số đơn thành công' => 'metrics.completed_reservations',
                'Số đơn hủy' => 'metrics.cancelled_reservations',
            ]),
            Layout::rows([
                DateTimer::make('from_date')
                    ->title('Từ ngày')
                    ->value(fn($query) => $query['from_date'] ?? null)
                    ->format('Y-m-d')
                    ->allowInput(),
                DateTimer::make('to_date')
                    ->title('Đến ngày')
                    ->value(fn($query) => $query['to_date'] ?? null)
                    ->format('Y-m-d')
                    ->allowInput(),
            ]),
            AdminReservationChartLayout::class,
        ];
    }
} 