<?php

namespace App\Orchid\Layouts\Product;

use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class ProductFillter extends Selection
{
    /**
     * @return string[]|Filter[]
     */
    public function filters(): iterable
    {
        return [];
    }
}
