<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

/**
 * Summary of Type
 * @property int $id
 * @property string $name
 * @property bool $isDeletable
 */

class Type extends Model
{
    use AsSource;
    protected $fillable = [
        'name',
    ];

    public function isDeletable()
    {
        $product = Product::query()->where('type_id', $this->id)->first();

        return $product ? false : true;
    }
}
