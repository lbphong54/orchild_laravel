<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\ReservationTable;
use App\Models\Restaurant;
use App\Models\RestaurantTable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required|exists:restaurants,id',
            'adults' => 'required|integer|min:1',
            'children' => 'required|integer|min:0',
            'reservation_time' => 'required|date|after:now',
            'special_request' => 'nullable|string|max:500',
            'table_ids' => 'required|array|exists:restaurant_tables,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Create reservation
        $reservation = Reservation::create([
            'customer_id' => Auth::user()->id,
            'restaurant_id' => $request->restaurant_id,
            'reservation_time' => $request->reservation_time,
            'num_adults' => $request->adults,
            'num_children' => $request->children,
            'special_request' => $request->special_request,
            'status' => 'pending',
            'is_paid' => false
        ]);

        // check table time is available
        $tableTime = ReservationTable::query()
            ->whereIn('restaurant_table_id', $request->table_ids)
            ->where('from_time', '<=', $request->reservation_time)
            ->where('to_time', '>=', Carbon::parse($request->reservation_time)->addHours(2))->first();
        if ($tableTime) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bàn ' . $tableTime->table->name . ' đã được đặt trong khoảng thời gian bạn chọn.'
            ], 400);
        }

        foreach ($request->table_ids as $tableId) {
            ReservationTable::create([
                'reservation_id' => $reservation->id,
                'restaurant_table_id' => $tableId,
                'from_time' => $request->reservation_time,
                'to_time' => Carbon::parse($request->reservation_time)->addHours(2)
            ]);
        }


        return response()->json([
            'status' => 'success',
            'message' => 'Reservation created successfully',
            'data' => $reservation
        ], 201);
    }


    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update($request->all());
        return response()->json($reservation);
    }
}
