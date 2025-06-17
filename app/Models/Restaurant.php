<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Screen\AsSource;

class Restaurant extends Model
{
    use AsSource;
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'address',
        'phone',
        'email',
        'opening_hours',
        'status',
    ];

    protected $casts = [
        'opening_hours' => 'array',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }
} 