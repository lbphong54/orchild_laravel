<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

/**
 * @property int $id
 * @property string $name
 * @property bool $isDeletable
 */

class Size extends Model
{
    use AsSource;
    protected $fillable = [
        'name',
    ];


    public function isDeletable()
    {
        $product = ProductDetail::query()->where('size_id', $this->id)->first();

        return $product ? false : true;
    }
}
