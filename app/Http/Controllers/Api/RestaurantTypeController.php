<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RestaurantType;
use Illuminate\Http\Request;

class RestaurantTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = RestaurantType::select('id', 'name')->get();

        return response()->json([
            'status' => 'success',
            'data' => $types
        ]);
    }
} 