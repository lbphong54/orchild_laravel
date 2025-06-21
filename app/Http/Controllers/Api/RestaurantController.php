<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $query = Restaurant::select('id', 'name', 'address', 'price_range')
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

        return response()->json([
            'status' => 'success',
            'data' => $restaurant
        ]);
    }
}
