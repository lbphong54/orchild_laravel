<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Orchid\Screen\AsSource;

class RestaurantType extends Model
{
    use AsSource;
    protected $fillable = [
        'name',
        'description',
        'image',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'image' => 'array',
    ];

    public function restaurants(): BelongsToMany
    {
        return $this->belongsToMany(Restaurant::class, 'restaurant_restaurant_type');
    }
} 