<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Orchid\Screen\AsSource;

class RestaurantTable extends Model
{
    use HasFactory, AsSource;

    protected $fillable = [
        'restaurant_id',
        'name',
        'min_capacity',
        'max_capacity',
        'status',
    ];

    protected $casts = [
        'min_capacity' => 'integer',
        'max_capacity' => 'integer',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
} 