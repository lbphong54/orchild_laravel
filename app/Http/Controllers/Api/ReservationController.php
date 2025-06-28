<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ReservationSuccessMail;
use App\Models\Reservation;
use App\Models\ReservationTable;
use App\Models\Restaurant;
use App\Models\RestaurantTable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:500',
            'email' => 'required|email|max:500',
            'phone' => 'required|string|max:11',
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

        foreach ($request->table_ids as $tableId) {
            ReservationTable::create([
                'reservation_id' => $reservation->id,
                'restaurant_table_id' => $tableId,
                'from_time' => $request->reservation_time,
                'to_time' => Carbon::parse($request->reservation_time)->addHours(2)
            ]);
        }

        $tableNames = RestaurantTable::whereIn('id', $request->table_ids)->pluck('name')->toArray();
        $tableNameString = implode(', ', $tableNames);

        Mail::to($request->email)->send(new ReservationSuccessMail(
            $request->adults,
            $request->children,
            $reservation->id,
            $request->reservation_time,
            $tableNameString
        ));

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

    /**
     * Lấy lịch sử đặt bàn của user đăng nhập, có phân trang.
     */
    public function history(Request $request)
    {
        $user = Auth::user();

        $perPage = $request->input('limit', 10);
        $page = $request->input('page', 1);

        $reservations = Reservation::with(['restaurant:id,name,address,phone', 'tables:id,name'])
            ->where('customer_id', $user->id)
            ->orderBy('reservation_time', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'status' => 'success',
            'data' => $reservations
        ]);
    }
}
