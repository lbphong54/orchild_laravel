<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $name
 * @property mixed $path
 * @property mixed $product_id
 * @property mixed $product_detail_id
 */

class Image extends Model
{
    protected $fillable = [
        'name',
        'product_id',
        'product_detail_id',
        'path',
    ];
}
