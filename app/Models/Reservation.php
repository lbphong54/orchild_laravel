<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Orchid\Screen\AsSource;

class Reservation extends Model
{
    use AsSource;
    protected $fillable = [
        'customer_id',
        'restaurant_id',
        'reservation_time',
        'num_adults',
        'num_children',
        'status',
        'special_request',
        'is_paid',
        'cancellled_at',
        'amount',
        'cofirm_paid',
    ];

    protected $casts = [
        'reservation_time' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function tables(): BelongsToMany
    {
        return $this->belongsToMany(RestaurantTable::class, 'reservation_tables');
    }

    public function getTableIdsAttribute(): array
    {
        return $this->tables->pluck('id')->toArray();
    }
}
