<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Orchid\Screen\AsSource;

class Amenity extends Model
{
    use AsSource;

    protected $fillable = [
        'name',
        'description',
        'key',
        'value'
    ];

    public function restaurants(): BelongsToMany
    {
        return $this->belongsToMany(Restaurant::class, 'restaurant_amenities')
            ->withPivot('value');
    }
} 