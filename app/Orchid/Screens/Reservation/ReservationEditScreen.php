<?php

namespace App\Orchid\Screens\Reservation;

use App\Models\Reservation;
use App\Models\Restaurant;
use App\Models\Customer;
use App\Models\ReservationTable;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\TextArea;

class ReservationEditScreen extends Screen
{
    public $reservation;

    public function name(): ?string
    {
        return $this->reservation->exists ? 'Chỉnh sửa đơn đặt bàn' : 'Thêm đơn đặt bàn mới';
    }

    public function query(Reservation $reservation): array
    {
        return [
            'reservation' => $reservation,
            'restaurants' => Restaurant::all(),
            'customers' => Customer::all(),
        ];
    }

    public function commandBar(): array
    {
        return [
            Button::make('Lưu')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->reservation->exists),

            Button::make('Cập nhật')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->reservation->exists),

            Button::make('Xóa')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->reservation->exists),
        ];
    }

    public function layout(): array
    {
        return [
            Layout::rows([
                Select::make('reservation.restaurant_id')
                    ->fromModel(Restaurant::class, 'name')
                    ->title('Nhà hàng')
                    ->required(),

                Select::make('reservation.customer_id')
                    ->fromModel(Customer::class, 'name')
                    ->title('Khách hàng')
                    ->required(),

                DateTimer::make('reservation.reservation_date')
                    ->title('Ngày đặt')
                    ->format('Y-m-d')
                    ->required(),

                Input::make('reservation.reservation_time')
                    ->title('Giờ đặt')
                    ->type('time')
                    ->required(),

                Input::make('reservation.number_of_guests')
                    ->title('Số khách')
                    ->type('number')
                    ->min(1)
                    ->required(),

                Select::make('reservation.status')
                    ->title('Trạng thái')
                    ->options([
                        'pending' => 'Chờ xác nhận',
                        'confirmed' => 'Đã xác nhận',
                        'cancelled' => 'Đã hủy',
                        'completed' => 'Hoàn thành',
                    ])
                    ->required()
                    ->disabled(in_array(optional($this->reservation)->status, ['completed', 'cancelled'])),

                TextArea::make('reservation.notes')
                    ->title('Ghi chú')
                    ->placeholder('Nhập ghi chú')
                    ->rows(3),
            ])
        ];
    }

    public function createOrUpdate(Reservation $reservation, Request $request)
    {
        $reservation->fill($request->get('reservation'))->save();

        return redirect()->route('platform.reservation.list');
    }

    public function remove(Reservation $reservation)
    {
        $reservation->delete();

        return redirect()->route('platform.reservation.list');
    }
}