<?php

namespace App\Orchid\Screens\Reservation;

use App\Models\Reservation;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\DateTimer;
use Illuminate\Http\Request;

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
                ->orderBy('id', 'desc')
                ->paginate()
        ];
    }

    public function commandBar(): array
    {
        return [
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
                    ->filter(),
                TD::make('customer.full_name', 'Khách hàng')
                    ->sort()
                    ->filter(),
                TD::make('reservation_time', 'Giờ đặt')
                    ->sort()
                    ->filter()
                    ->render(fn(Reservation $reservation) => $reservation->reservation_time->format('d/m/Y-H:i')),
                TD::make('number_of_guests', 'Số khách')
                    ->sort()
                    ->filter()
                    ->alignRight()
                    ->render(fn($reservation) => ($reservation->num_adults ?? 0) + ($reservation->num_children ?? 0)),
                TD::make('status', 'Trạng thái')
                    ->sort()
                    ->filter()
                    ->render(fn(Reservation $reservation) => $this->getStatusLabel($reservation->status)),
                TD::make('created_at', 'Ngày tạo')
                    ->sort()
                    ->render(fn(Reservation $reservation) => $reservation->created_at->format('d/m/Y H:i')),
                TD::make('actions', '')
                    ->align(TD::ALIGN_CENTER)
                    ->width('100px')
                    ->render(fn(Reservation $reservation) => ModalToggle::make('')
                        ->modal('reservationDetails')
                        ->method('update')
                        ->asyncParameters([
                            'reservation' => $reservation->id,
                        ])
                        ->icon('eye')),
            ]),

            Layout::modal('reservationDetails', [
                Layout::rows([
                    Label::make('reservation.customer.full_name')
                        ->type('text')
                        ->title('Khách hàng'),
                    Label::make('reservation.customer.phone')
                        ->type('text')
                        ->title('Số điện thoại'),
                    DateTimer::make('reservation.reservation_time')
                        ->title('Thời gian đặt bàn')
                        ->format('Y-m-d H:i')
                        ->enableTime()
                        ->format24hr(),
                    Input::make('reservation.num_adults')
                        ->type('number')
                        ->title('Số người lớn'),
                    Input::make('reservation.num_children')
                        ->type('number')
                        ->title('Số trẻ em'),

                    Select::make('reservation.status')
                        ->title('Trạng thái')
                        ->options([
                            'pending' => 'Chờ xác nhận',
                            'confirmed' => 'Đã xác nhận',
                            'cancelled' => 'Đã hủy',
                            'completed' => 'Hoàn thành',
                        ]),
                    TextArea::make('reservation.special_request')
                        ->title('Ghi chú'),
                ]),
            ])->title('Chi tiết đơn đặt bàn')->async('asyncGetReservation'),
        ];
    }

    private function getStatusLabel($status)
    {
        return match ($status) {
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'cancelled' => 'Đã hủy',
            'completed' => 'Hoàn thành',
            default => 'Không xác định'
        };
    }

    public function asyncGetReservation(Reservation $reservation)
    {
        return [
            'reservation' => $reservation
        ];
    }

    public function update(Request $request)
    {
        $reservation = Reservation::findOrFail($request->get('reservation'));

        $data = $request->input('reservation');
        $reservation->status = $data['status'];
        $reservation->reservation_time = $data['reservation_time'];
        $reservation->num_adults = $data['num_adults'] ?? 0;
        $reservation->num_children = $data['num_children'] ?? 0;
        $reservation->special_request = $data['special_request'] ?? null;

        $reservation->save();


        return redirect()->route('platform.reservation.list');
    }
}