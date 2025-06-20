<?php

namespace App\Orchid\Screens\Restaurant;

use App\Models\Restaurant;
use App\Models\RestaurantType;
use App\Models\Amenity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Components\Cells\Text;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Upload;
use Orchid\Support\Facades\Toast;

class RestaurantProfileScreen extends Screen
{

    public function name(): ?string
    {
        return $this->query()['restaurant'] ? 'Thông tin nhà hàng' : 'Tạo thông tin nhà hàng';
    }

    public function description(): ?string
    {
        return $this->query()['restaurant'] ? 'Cập nhật thông tin nhà hàng của bạn' : 'Tạo thông tin nhà hàng mới';
    }

    public function query(): array
    {
        $restaurant = Restaurant::where('user_id', Auth::user()->id)->first();

        return [
            'restaurant' => $restaurant,
            'types' => RestaurantType::all(),
            'amenities' => Amenity::all(),
        ];
    }

    public function commandBar(): array
    {
        return [
            Button::make($this->query()['restaurant'] ? 'Cập nhật' : 'Tạo mới')
                ->icon('note')
                ->method($this->query()['restaurant'] ? 'update' : 'create')
        ];
    }

    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('restaurant.name')
                    ->title('Tên nhà hàng')
                    ->placeholder('Nhập tên nhà hàng')
                    ->required(),

                Select::make('restaurant.status')
                    ->title('Trạng thái')
                    ->options([
                        'active' => 'Hoạt động',
                        'inactive' => 'Không hoạt động'
                    ])
                    ->value('active')
                    ->help('Chọn trạng thái hoạt động của nhà hàng'),

                Select::make('restaurant.types.')
                    ->fromModel(RestaurantType::class, 'name')
                    ->title('Loại nhà hàng')
                    ->multiple()
                    ->help('Chọn các loại nhà hàng'),

                TextArea::make('restaurant.summary')
                    ->title('Tóm tắt')
                    ->placeholder('Nhập tóm tắt')
                    ->rows(5),

                Input::make('restaurant.address')
                    ->title('Địa chỉ')
                    ->placeholder('Nhập địa chỉ')
                    ->required(),

                Input::make('restaurant.phone')
                    ->title('Số điện thoại')
                    ->placeholder('Nhập số điện thoại')
                    ->required(),

                Input::make('restaurant.email')
                    ->title('Email')
                    ->placeholder('Nhập email')
                    ->type('email')
                    ->required(),

                Upload::make('restaurant.menu_images')
                    ->title('Hình ảnh menu')
                    ->multiple()
                    ->help('Tải lên hình ảnh menu nhà hàng'),

                // Quill::make('restaurant.regulation')
                //     ->title('Quy định')
                //     ->placeholder('Nhập quy định')
                //     ->required(),

                // Quill::make('restaurant.parking_info')
                //     ->title('Thông tin chỗ để xe')
                //     ->placeholder('Nhập thông tin chỗ để xe')
                //     ->required(),

                Select::make('restaurant.amenities')
                    ->fromModel(Amenity::class, 'name')
                    ->title('Tiện ích')
                    ->multiple()
                    ->help('Chọn các tiện ích có sẵn tại nhà hàng'),

                Upload::make('restaurant.images')
                    ->title('Hình ảnh')
                    ->multiple(),

                // Giờ hoạt động
                Label::make('')->value('Thời gian hoạt động'),
                Group::make([
                    Input::make('restaurant.opening_hours.monday.open')
                        ->type('time')
                        ->placeholder('10:00')
                        ->mask('99:99')
                        ->title('Giờ mở cửa'),
                    Input::make('restaurant.opening_hours.monday.close')
                        ->title(' ')
                        ->type('time')
                        ->placeholder('23:00')
                        ->mask('99:99')
                        ->title('Giờ đóng cửa'),
                ]),
                Group::make([
                    Input::make('restaurant.opening_hours.tuesday.open')
                        ->type('time')
                        ->placeholder('10:00')
                        ->mask('99:99'),
                    Input::make('restaurant.opening_hours.tuesday.close')
                        ->type('time')
                        ->placeholder('23:00')
                        ->mask('99:99'),
                ]),

                Group::make([
                    Input::make('restaurant.opening_hours.wednesday.open')
                        ->type('time')
                        ->placeholder('10:00')
                        ->mask('99:99'),
                    Input::make('restaurant.opening_hours.wednesday.close')
                        ->type('time')
                        ->placeholder('23:00')
                        ->mask('99:99'),
                ]),

                Group::make([
                    Input::make('restaurant.opening_hours.thursday.open')
                        ->type('time')
                        ->placeholder('10:00')
                        ->mask('99:99'),
                    Input::make('restaurant.opening_hours.thursday.close')
                        ->type('time')
                        ->placeholder('23:00')
                        ->mask('99:99'),
                ]),

                Group::make([
                    Input::make('restaurant.opening_hours.friday.open')
                        ->type('time')
                        ->placeholder('10:00')
                        ->mask('99:99'),
                    Input::make('restaurant.opening_hours.friday.close')
                        ->type('time')
                        ->placeholder('23:00')
                        ->mask('99:99'),
                ]),

                Group::make([
                    Input::make('restaurant.opening_hours.saturday.open')
                        ->type('time')
                        ->placeholder('10:00')
                        ->mask('99:99'),
                    Input::make('restaurant.opening_hours.saturday.close')
                        ->type('time')
                        ->placeholder('23:00')
                        ->mask('99:99'),
                ]),

                Group::make([
                    Input::make('restaurant.opening_hours.sunday.open')
                        ->type('time')
                        ->placeholder('10:00')
                        ->mask('99:99'),
                    Input::make('restaurant.opening_hours.sunday.close')
                        ->type('time')
                        ->placeholder('23:00')
                        ->mask('99:99'),
                ]),

                // Mô tả
                // Quill::make('restaurant.description')
                //     ->title('Thông tin chi tiết nhà hànghàng')
                //     ->placeholder('Nhập thông tin')
                //     ->required(),
            ])
        ];
    }

    public function create(Request $request)
    {
        $request->validate([
            'restaurant.name' => 'required|string|max:100',
            'restaurant.address' => 'required|string|max:255',
            'restaurant.phone' => 'nullable|string|max:20',
            'restaurant.email' => 'nullable|email|max:100',
            'restaurant.summary' => 'nullable|string',
            'restaurant.description' => 'nullable|string',
            'restaurant.regulation' => 'nullable|string',
            'restaurant.parking_info' => 'nullable|string|max:255',
            'restaurant.opening_hours' => 'nullable|array',
            'restaurant.status' => 'nullable|in:active,inactive',
        ]);

        $restaurantData = $request->get('restaurant');
        $restaurantData['user_id'] = Auth::user()->id;
        
        // Handle opening hours
        if (isset($restaurantData['opening_hours'])) {
            $restaurantData['opening_hours'] = json_encode($restaurantData['opening_hours']);
        }

        $restaurant = Restaurant::create($restaurantData);

        // Handle relationships
        if ($request->has('restaurant.types')) {
            $restaurant->types()->sync($request->get('restaurant.types'));
        }

        if ($request->has('restaurant.amenities')) {
            $restaurant->amenities()->sync($request->get('restaurant.amenities'));
        }

        // Handle images
        if ($request->has('restaurant.images')) {
            $restaurant->images()->createMany(
                collect($request->get('restaurant.images'))->map(function ($image) {
                    return ['image_path' => $image];
                })->toArray()
            );
        }

        Toast::success('Thông tin nhà hàng đã được tạo thành công.');
        return redirect()->route('platform.restaurant.profile');
    }

    public function update(Request $request)
    {
        $restaurant = $this->query()['restaurant'];
        $request->validate([
            'restaurant.name' => 'required|string|max:100',
            'restaurant.address' => 'required|string|max:255',
            'restaurant.phone' => 'nullable|string|max:20',
            'restaurant.email' => 'nullable|email|max:100',
            'restaurant.summary' => 'nullable|string',
            'restaurant.description' => 'nullable|string',
            'restaurant.regulation' => 'nullable|string',
            'restaurant.parking_info' => 'nullable|string|max:255',
            'restaurant.opening_hours' => 'nullable|array',
            'restaurant.status' => 'nullable|in:active,inactive',
        ]);

        $restaurantData = $request->get('restaurant');

        $restaurant->update($restaurantData);

        // Handle relationships
        if ($request->has('restaurant.types')) {
            $restaurant->types()->sync($request->get('restaurant.types'));
        }

        if ($request->has('restaurant.amenities')) {
            $restaurant->amenities()->sync($request->get('restaurant.amenities'));
        }

        // Handle images
        if ($request->has('restaurant.images')) {
            // Delete old images if needed
            // $restaurant->images()->delete();
            
            $restaurant->images()->createMany(
                collect($request->get('restaurant.images'))->map(function ($image) {
                    return ['image_path' => $image];
                })->toArray()
            );
        }

        Toast::success('Thông tin nhà hàng đã được cập nhật thành công.');
        return redirect()->route('platform.restaurant.profile');
    }
}
