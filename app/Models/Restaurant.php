<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Restaurant extends Model
{
    protected $fillable = [
        'name',
        'description',
        'address',
        'phone',
        'email',
        'opening_hours',
        'closing_hours',
        'status',
    ];

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(RestaurantType::class, 'restaurant_restaurant_type');
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'restaurant_amenities')
            ->withPivot('value');
    }

    public function images(): HasMany
    {
        return $this->hasMany(RestaurantImage::class);
    }

    public function tables(): HasMany
    {
        return $this->hasMany(RestaurantTable::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
} 