<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

/**
 * Summary of Category
 * @property int $id
 * @property string $name
 * @property bool $isDeletable
 */

class Category extends Model
{
    use AsSource;
    protected $table = 'categories';

    public function isDeletable()
    {
        $product = Product::query()->where('category_id', $this->id)->first();

        return $product ? false : true;
    }
}
