<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::with(['types', 'amenities', 'images'])
            ->where('status', 'active')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $restaurants
        ]);
    }

    public function show($id)
    {
        $restaurant = Restaurant::with(['types', 'amenities', 'images', 'tables'])
            ->where('status', 'active')
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $restaurant
        ]);
    }
} 