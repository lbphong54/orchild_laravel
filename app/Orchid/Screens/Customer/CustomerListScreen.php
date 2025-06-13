<?php

namespace App\Orchid\Screens\Customer;

use App\Models\Customer;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;

class CustomerListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'customers' => Customer::orderBy('id', 'desc')
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
        return 'Quản lý khách hàng';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            // Link::make('Thêm mới')
            //     ->icon('pencil')
            //     ->route('platform.customer.edit'),
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
            Layout::table('customers', [
                TD::make('id', 'ID')
                    ->sort()
                    ->filter(TD::FILTER_NUMERIC)
                    ->render(fn (Customer $customer) => Link::make($customer->id)
                        ->route('platform.customer.edit', $customer)),

                TD::make('full_name', 'Tên khách hàng')
                    ->sort()
                    ->filter(TD::FILTER_TEXT),

                TD::make('email', 'Email')
                    ->sort()
                    ->filter(TD::FILTER_TEXT),

                TD::make('phone', 'Số điện thoại')
                    ->sort()
                    ->filter(TD::FILTER_TEXT),

                TD::make('created_at', 'Ngày tạo')
                    ->sort()
                    ->render(fn (Customer $customer) => $customer->created_at->format('d/m/Y H:i')),
            ]),
        ];
    }
} 