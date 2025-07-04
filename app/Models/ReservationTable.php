<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Orchid\Screen\AsSource;

class ReservationTable extends Model
{
    use AsSource;
    protected $fillable = [
        'reservation_id',
        'restaurant_table_id',
        'from_time',
        'to_time',
    ];

    protected $casts = [
        'from_time' => 'datetime',
        'to_time' => 'datetime',
    ];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(RestaurantTable::class, 'restaurant_table_id');
    }
}