<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RestaurantTable;

class TableController extends Controller
{
    // public function index(Request $request ) {
    //     $table = RestaurantTable::query()->where('restaurant_id',  $request->id)->get();

    //     return response()->json($table);
    // }
    public function index(Request $request ) {
        if (!$request->id) {
            return response()->json(['message' => 'Thiếu id nhà hàng'], 400);
        }
        $table = RestaurantTable::where('restaurant_id', $request->id)->get();
        return response()->json($table);
    }
}
