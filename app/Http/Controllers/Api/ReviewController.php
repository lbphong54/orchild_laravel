<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index($id)
    {
        $reviews = Review::where('restaurant_id', $id)->with('customer')->latest()->paginate(10);

        return response()->json($reviews);
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $customer = $request->user();
        $restaurant = Restaurant::findOrFail($id);

        $existingReview = Review::where('customer_id', $customer->id)
            ->where('restaurant_id', $restaurant->id)
            ->where('reservation_id', $request->reservation_id)
            ->first();

        if ($existingReview) {
            return response()->json(['message' => 'You have already reviewed this reservation.'], 409);
        }

        $review = $restaurant->reviews()->create([
            'customer_id' => $customer->id,
            'reservation_id' => $request->reservation_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        $restaurant->update(['rating' => $restaurant->reviews()->avg('rating')]);

        return response()->json($review, 201);
    }
} 