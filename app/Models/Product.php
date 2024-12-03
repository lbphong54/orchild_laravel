<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Screen\AsSource;

/**
 * Summary of Product
 * @property int $id
 * @property string $name
 * @property string $code
 * @property mixed $partner_id
 * @property mixed $description
 * @property mixed $price
 * @property mixed $category_id
 * @property mixed $type_id
 * @property mixed $stock
 */

class Product extends Model
{
    use HasFactory, AsSource, Filterable;

    protected $fillable = [
        'name',
        'code',
        'partner_id',
        'description',
        'price',
        'category_id',
        'type_id',
        'stock',
    ];

    protected $allowedFilters = [
        'id'         => Where::class,
        'code'       => Like::class,
        'name'       => Like::class,
        'partner_id' => Where::class,
        'description'=> Like::class,
        'price'      => Where::class,
        'stock'      => Where::class,
        'category_id'=> Where::class,
        'type_id'    => Where::class,
    ];

    protected $allowedSorts = [
        'id',
        'name',
        'price',
        'stock',
    ];


    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id','id');
    }   

}
