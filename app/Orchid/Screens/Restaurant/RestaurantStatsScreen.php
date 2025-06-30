<?php

namespace App\Orchid\Screens\Restaurant;

use App\Models\Restaurant;
use App\Models\RestaurantTable;
use App\Models\Reservation;
use App\Models\Review;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\LineChart;
use Orchid\Screen\Fields\Metrics;
use Carbon\Carbon;
use App\Orchid\Layouts\RestaurantReservedChartLayout;

class RestaurantStatsScreen extends Screen
{
    public function name(): ?string
    {
        return 'Thống kê nhà hàng';
    }

    public function query(): array
    {
        $user = Auth::user();
        $restaurantId = Restaurant::where('user_id', $user->id)->first()->id;
        if (!$restaurantId) {
            return [
                'error' => 'Không tìm thấy nhà hàng của bạn.'
            ];
        }

        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();

        $totalReservedMonth = Reservation::where('restaurant_id', $restaurantId)
            // ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();
        $totalReservedToday = Reservation::where('restaurant_id', $restaurantId)
            // ->whereDate('created_at', $today)
            ->count();

        // Số bàn đã đặt trong 7 ngày gần nhất
        $dates = collect(range(0, 6))->map(function ($i) use ($today) {
            return $today->copy()->subDays(6 - $i)->format('Y-m-d');
        });
        $labels = $dates->map(fn($d) => Carbon::parse($d)->format('d/m'))->toArray();
        $values = $dates->map(function ($date) use ($restaurantId) {
            return Reservation::where('restaurant_id', $restaurantId)
                ->whereDate('created_at', $date)
                ->count();
        })->toArray();

        return [
            'metrics' => [
                'reserved_month' => [
                    'value' => $totalReservedMonth,
                    'title' => 'Tổng số bàn đã đặt trong tháng',
                ],
                'reserved_today' => [
                    'value' => $totalReservedToday,
                    'title' => 'Tổng số bàn đã đặt trong ngày',
                ],
            ],
            'reserved_chart' => [
                [
                    'name' => 'Bàn đã đặt',
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
                'Bàn đã đặt trong tháng' => 'metrics.reserved_month',
                'Bàn đã đặt trong ngày' => 'metrics.reserved_today',
            ]),
            RestaurantReservedChartLayout::class,
        ];
    }
} 