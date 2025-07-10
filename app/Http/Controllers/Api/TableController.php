<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReservationTable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\RestaurantTable;

class TableController extends Controller
{
    // public function index(Request $request ) {
    //     $table = RestaurantTable::query()->where('restaurant_id',  $request->id)->get();

    //     return response()->json($table);
    // }
    public function index(Request $request)
    {
        if (!$request->id) {
            return response()->json(['message' => 'Thiếu id nhà hàng'], 400);
        }

        if ($request->time) {
            $time = new Carbon($request->time);

            $talbeReservation = ReservationTable::query()
                ->where('from_time', '<=', $time)
                ->where('to_time', '>=', $time)
                ->whereHas('table', function ($query) use ($request) {
                    $query->where('restaurant_id', $request->id);
                })
                ->whereDoesntHave('reservation', function ($query) {
                    $query->whereNotIn('status', ['pending', 'confirmed']);
                })
                ->get();
            $tableIds = $talbeReservation->pluck('restaurant_table_id')->toArray();

            $tables = RestaurantTable::where('restaurant_id', $request->id)
                ->get();

            $result = collect();

            foreach ($tables as $table) {
                if (in_array($table->id, $tableIds)) {
                    $table['disable'] = true;
                } else {
                    $table['disable'] = false;
                }
                $result->push($table);
            }

            return response()->json($result);
        }
        $table = RestaurantTable::where('restaurant_id', $request->id)->get();
        return response()->json($table);
    }
}
