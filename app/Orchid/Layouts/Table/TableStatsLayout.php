<?php

namespace App\Orchid\Layouts\Table;

use App\Models\RestaurantTable;
use Orchid\Screen\Layout;
use Orchid\Screen\Repository;
use Illuminate\Support\Facades\Auth;

class TableStatsLayout extends Layout
{
    public function build(Repository $repository)
    {
        $restaurantId = Auth::user()->restaurant_id;
        
        $totalTables = RestaurantTable::where('restaurant_id', $restaurantId)->count();
        $availableTables = RestaurantTable::where('restaurant_id', $restaurantId)
            ->where('status', 'available')->count();
        $occupiedTables = RestaurantTable::where('restaurant_id', $restaurantId)
            ->where('status', 'occupied')->count();
        $reservedTables = RestaurantTable::where('restaurant_id', $restaurantId)
            ->where('status', 'reserved')->count();

        return view('platform.tables.stats', compact(
            'totalTables',
            'availableTables', 
            'occupiedTables',
            'reservedTables'
        ));
    }
} 