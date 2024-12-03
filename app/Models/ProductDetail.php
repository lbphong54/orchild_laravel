<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class ProductDetail extends Model
{
    use AsSource;
    protected $fillable = [
        'product_id',
        'color_id',
        'size_id',
        'images',
        'stock',
    ];
}
