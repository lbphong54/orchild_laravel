<?php

namespace App\Orchid\Screens\Customer;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Reservation;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Actions\Button;

class CustomerEditScreen extends Screen
{
    /**
     * @var Customer
     */
    public $customer;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Customer $customer): iterable
    {
        return [
            'customer' => $customer,
            'reservation' => Reservation::where('customer_id', $customer->id)
                ->orderBy('id', 'desc')
                ->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->customer->exists ? 'Chỉnh sửa khách hàng' : 'Thêm khách hàng mới';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Lưu')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->customer->exists),

            Button::make('Xóa')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->customer->exists && !$this->customer->reservations()->exists()),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Label::make('customer.full_name')
                    ->title('Tên khách hàng')
                    ->required(),

                Label::make('customer.email')
                    ->type('email')
                    ->title('Email')
                    ->required(),

                Label::make('customer.phone')
                    ->title('Số điện thoại')
                    ->required(),
            ]),

            Layout::table('reservation', [
                TD::make('id', 'ID')
                    ->sort()
                    ->filter(TD::FILTER_NUMERIC),
                TD::make('status', 'Trạng thái')
                    ->sort()
                    ->render(fn(Reservation $reservation) => $this->getStatusLabel($reservation->status)),

                TD::make('reservation_time', 'Ngày đặt')
                    ->sort()
                    ->render(fn(Reservation $reservation) => $reservation->created_at->format('d/m/Y H:i')),

                TD::make('created_at', 'Ngày tạo')
                    ->sort()
                    ->render(fn(Reservation $reservation) => $reservation->created_at->format('d/m/Y H:i')),

                TD::make('')
                    ->align(TD::ALIGN_CENTER)
                    ->width('100px')
                    ->render(fn(Reservation $reservation) => ModalToggle::make('')
                        ->icon('eye')
                        ->modal('reservationDetails')
                        ->asyncParameters([
                            'reservation' => $reservation->id,
                        ])),
            ])->title('Danh sách đơn hàng'),

            Layout::modal('reservationDetails', [
                Layout::rows([
                    Label::make('reservation_restaurant_name')->title('Nhà hàng'),
                    Label::make('reservation_customer_full_name')->title('Khách hàng'),
                    Label::make('reservation_reservation_time')->title('Thời gian đặt bàn'),
                    Label::make('reservation_num_adults')->title('Số người lớn'),
                    Label::make('reservation_num_children')->title('Số trẻ em'),
                    Label::make('reservation_status')->title('Trạng thái'),
                    Label::make('reservation_special_request')->title('Ghi chú'),
                ]),
            ])->title('Chi tiết đơn đặt bàn')->async('asyncGetReservation')->withoutApplyButton(),
        ];
    }

    public function asyncGetReservation(Reservation $reservation)
    {
        return [
            'reservation_restaurant_name' => $reservation->restaurant->name,
            'reservation_customer_full_name' => $reservation->customer->full_name,
            'reservation_reservation_time' => $reservation->reservation_time->format('d/m/Y H:i'),
            'reservation_num_adults' => $reservation->num_adults,
            'reservation_num_children' => $reservation->num_children,
            'reservation_status' => $this->getStatusLabel($reservation->status),
            'reservation_special_request' => $reservation->special_request,
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

    /**
     * @param Customer $customer
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Customer $customer, Request $request)
    {
        $customer->fill($request->get('customer'))->save();

        return redirect()->route('platform.customer.list');
    }

    /**
     * @param Customer $customer
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('platform.customer.list');
    }
}