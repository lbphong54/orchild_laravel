<?php

namespace App\Orchid\Layouts\Table;

use App\Models\Restaurant;
use App\Models\RestaurantTable;
use Orchid\Screen\Layout;
use Orchid\Screen\Repository;
use Illuminate\Support\Facades\Auth;

class TableGridViewLayout extends Layout
{
    public function build(Repository $repository)
    {
        $tables = RestaurantTable::where('restaurant_id', Restaurant::where('user_id', Auth::user()->id)->first()->id)
            ->orderBy('name')
            ->get();

        return view('platform.tables.grid', compact('tables'));
    }
} 