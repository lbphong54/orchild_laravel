<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RestaurantType;
use Illuminate\Http\Request;
use Orchid\Attachment\Models\Attachment;

class RestaurantTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = RestaurantType::select('id', 'name', 'image')->get();

        foreach ($types as $type) {
            $type->image = $type->image ? Attachment::find($type->image[0])->url() : null;
        }

        return response()->json([
            'status' => 'success',
            'data' => $types
        ]);
    }
} 