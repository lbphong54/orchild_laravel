<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

/**
 * Summary of Color
 * @property int $id
 * @property string $name
 * @property string $color
 * @property bool $isDeletable
 */

class Color extends Model
{
    use AsSource;

    protected $fillable = [
        'name',
        'color',
    ];

    public function isDeletable()
    {
        $product = ProductDetail::query()->where('color_id', $this->id)->first();

        return $product ? false : true;
    }
}
