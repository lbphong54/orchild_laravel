<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Orchid\Attachment\Models\Attachment;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $query = Restaurant::select('id', 'name', 'address', 'price_range', 'rating', 'avatar')
            ->with(['types']);
            // ->where('status', 'active');

        // Filter by type_id if provided
        if ($request->has('type_id')) {
            $query->whereHas('types', function ($q) use ($request) {
                $q->where('types.id', $request->type_id);
            });
        }

        // Get pagination parameters
        $perPage = $request->get('limit', 10);
        $page = $request->get('page', 1);

        $restaurants = $query->paginate($perPage, ['*'], 'page', $page);

        $restaurants->getCollection()->transform(function ($restaurant) {

            $avatarUrl = null;
            if ($restaurant->avatar) {
                $attachment = Attachment::find($restaurant->avatar[0]);
                $avatarUrl = $attachment ? $attachment->url() : null;
            }

            $restaurant->avatar = $avatarUrl;
            return $restaurant;
        });

        return response()->json([
            'status' => 'success',
            'data' => $restaurants
        ]);
    }

    public function show($id)
    {
        $restaurant = Restaurant::with([
            'types',
            'amenities',
            'restaurantTables:id,restaurant_id,name,min_capacity,max_capacity'
        ])
            // ->where('status', 'active')
            ->findOrFail($id);

        $restaurant->avatar = $restaurant->avatar ? Attachment::find($restaurant->avatar[0])->url() : null;
        if ($restaurant->images) {
            $restaurant->images = array_map(function ($image) {
                return Attachment::find($image)->url();
            }, $restaurant->images);
        }

        if ($restaurant->menu_images) {
            $restaurant->menu_images = array_map(function ($image) {
                return Attachment::find($image)->url();
            }, $restaurant->menu_images);
        }

        return response()->json([
            'status' => 'success',
            'data' => $restaurant
        ]);
    }

    public function related($id)
{
    $restaurant = Restaurant::find($id);
    if (!$restaurant) {
        return response()->json(['data' => []]);
    }

    // Lấy 4 nhà hàng khác bất kỳ (có thể thêm điều kiện type nếu muốn)
    $related = Restaurant::where('id', '!=', $id)->inRandomOrder()->limit(4)->get();

    // Xử lý avatar thành đường dẫn ảnh
    $related = $related->map(function ($item) {
        // Nếu avatar là mảng, lấy phần tử đầu tiên
        
        $avatarUrl = null;
        if ($item->avatar) {
            $attachment = Attachment::find($item->avatar[0]);
            $avatarUrl = $attachment ? $attachment->url() : null;
        }

        $item->avatar = $avatarUrl;
        return $item;
    });

    return response()->json(['data' => $related]);
}
}
