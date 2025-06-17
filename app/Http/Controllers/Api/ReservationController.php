<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Restaurant;
use Illuminate\Http\Request;
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
            'special_request' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $restaurant = Restaurant::findOrFail($request->restaurant_id);
        
        // Calculate total guests
        $totalGuests = $request->adults + $request->children;

        // Create reservation
        $reservation = Reservation::create([
            'restaurant_id' => $request->restaurant_id,
            'reservation_time' => $request->reservation_time,
            'number_of_guests' => $totalGuests,
            'special_request' => $request->special_request,
            'status' => 'pending'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Reservation created successfully',
            'data' => $reservation
        ], 201);
    }
} 