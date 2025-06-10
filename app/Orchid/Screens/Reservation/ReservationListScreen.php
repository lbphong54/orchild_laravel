<?php

namespace App\Orchid\Screens\Reservation;

use App\Models\Reservation;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;

class ReservationListScreen extends Screen
{
    public function name(): ?string
    {
        return 'Quản lý đơn đặt bàn';
    }

    public function query(): array
    {
        return [
            'reservations' => Reservation::with(['restaurant', 'customer'])
                ->filters()
                ->defaultSort('id', 'desc')
                ->paginate()
        ];
    }

    public function commandBar(): array
    {
        return [
            Link::make('Thêm mới')
                ->icon('plus')
                ->route('platform.reservation.edit')
        ];
    }

    public function layout(): array
    {
        return [
            Layout::table('reservations', [
                TD::make('id', 'ID')
                    ->sort()
                    ->filter(),
                TD::make('restaurant.name', 'Nhà hàng')
                    ->sort()
                    ->filter()
                    ->render(fn (Reservation $reservation) => Link::make($reservation->restaurant->name)
                        ->route('platform.restaurant.edit', $reservation->restaurant)),
                TD::make('customer.name', 'Khách hàng')
                    ->sort()
                    ->filter(),
                TD::make('reservation_date', 'Ngày đặt')
                    ->sort()
                    ->filter()
                    ->render(fn (Reservation $reservation) => $reservation->reservation_date->format('d/m/Y')),
                TD::make('reservation_time', 'Giờ đặt')
                    ->sort()
                    ->filter()
                    ->render(fn (Reservation $reservation) => $reservation->reservation_time->format('H:i')),
                TD::make('number_of_guests', 'Số khách')
                    ->sort()
                    ->filter(),
                TD::make('status', 'Trạng thái')
                    ->sort()
                    ->filter()
                    ->render(fn (Reservation $reservation) => $this->getStatusLabel($reservation->status)),
                TD::make('created_at', 'Ngày tạo')
                    ->sort()
                    ->render(fn (Reservation $reservation) => $reservation->created_at->format('d/m/Y H:i')),
            ])
        ];
    }

    private function getStatusLabel($status)
    {
        return match($status) {
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'cancelled' => 'Đã hủy',
            'completed' => 'Hoàn thành',
            default => 'Không xác định'
        };
    }
} 