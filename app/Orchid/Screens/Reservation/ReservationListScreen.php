<?php

namespace App\Orchid\Screens\Reservation;

use App\Jobs\SendReservationCancellationJob;
use App\Models\Reservation;
use App\Models\ReservationTable;
use App\Models\Restaurant;
use App\Models\RestaurantTable;
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
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservationListScreen extends Screen
{
    public function name(): ?string
    {
        return 'Quản lý đơn đặt bàn';
    }

    public function query(): array
    {
        $restaurantId = Restaurant::where('user_id', Auth::user()->id)->value('id');

        return [
            'reservations' => Reservation::with(['restaurant', 'customer', 'tables'])
                ->where('restaurant_id', $restaurantId)
                ->orderBy('id', 'desc')
                ->paginate(),
        ];
    }

    public function commandBar(): array
    {
        return [
        ModalToggle::make('Tạo đơn mới')
            ->modal('createReservation')
            ->method('create')
            ->icon('plus'),
        ];
    }

    public function layout(): array
    {
        $restaurantId = Restaurant::where('user_id', Auth::user()->id)->first();

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
                        ->required()
                        ->enableTime()
                        ->format24hr(),
                    Input::make('reservation.num_adults')
                        ->type('number')
                        ->required()
                        ->title('Số người lớn'),
                    Input::make('reservation.num_children')
                        ->type('number')
                        ->required()
                        ->title('Số trẻ em'),

                    Select::make('reservation.table_ids')
                        ->title('Bàn đặt')
                        ->required()
                        ->fromQuery(RestaurantTable::where('restaurant_id', $restaurantId->id), 'name')
                        ->multiple()
                        ->help('Chọn bàn đặt'),

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

            Layout::modal('createReservation', [
                Layout::rows([
                    DateTimer::make('reservation.reservation_time')
                        ->title('Thời gian đặt bàn')
                        ->format('Y-m-d H:i')
                        ->required()
                        ->enableTime()
                        ->format24hr(),
                    Input::make('reservation.num_adults')
                        ->type('number')
                        ->required()
                        ->title('Số người lớn'),
                    Input::make('reservation.num_children')
                        ->type('number')
                        ->required()
                        ->title('Số trẻ em'),

                    Select::make('reservation.table_ids')
                        ->title('Bàn đặt')
                        ->required()
                        ->fromQuery(RestaurantTable::where('restaurant_id', $restaurantId->id), 'name')
                        ->multiple()
                        ->help('Chọn bàn đặt'),

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
            ])->title('Tạo đơn đặt bàn')->applyButton('Tạo mới')->method('create'),
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
        $oldStatus = $reservation->status;

        $data = $request->input('reservation');
        $reservation->status = $data['status'];
        $reservation->reservation_time = $data['reservation_time'];
        $reservation->num_adults = $data['num_adults'] ?? 0;
        $reservation->num_children = $data['num_children'] ?? 0;
        $reservation->special_request = $data['special_request'] ?? null;

        $reservation->save();

        // Gửi email thông báo hủy đơn hàng nếu trạng thái thay đổi thành cancelled
        if ($oldStatus !== 'cancelled' && $data['status'] === 'cancelled') {
            $now = Carbon::now();
            $hoursBeforeReservation = $now->diffInHours($reservation->reservation_time, false);
            
            // Chỉ gửi email nếu chưa quá thời gian đặt bàn
            if ($hoursBeforeReservation >= 0) {
                SendReservationCancellationJob::dispatch($reservation, $now);
            }
        }

        if (isset($data['table_ids'])) {
            $pivotData = array_fill_keys($data['table_ids'], ['from_time' => $reservation->reservation_time, 'to_time' => $reservation->reservation_time->addHours(2)]);
            $reservation->tables()->sync($pivotData);
        }

        return redirect()->route('platform.reservation.list');
    }

    public function create(Request $request)
    {
        $data = $request->input('reservation');
        $restaurantId = Restaurant::where('user_id', Auth::user()->id)->value('id');

        $reservation = new Reservation();
        $reservation->restaurant_id = $restaurantId;
        $reservation->status = $data['status'] ?? 'pending';
        $reservation->reservation_time = $data['reservation_time'];
        $reservation->num_adults = $data['num_adults'] ?? 0;
        $reservation->num_children = $data['num_children'] ?? 0;
        $reservation->special_request = $data['special_request'] ?? null;
        $reservation->save();

        if (isset($data['table_ids'])) {
            $pivotData = array_fill_keys($data['table_ids'], [
                'from_time' => $reservation->reservation_time,
                'to_time' => now()->parse($reservation->reservation_time)->addHours(2),
            ]);
            $reservation->tables()->sync($pivotData);
        }

        return redirect()->route('platform.reservation.list');
    }
}
