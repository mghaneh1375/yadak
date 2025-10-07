<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * An Eloquent Model: 'ProductItem'
 *
 * @property integer $id
 * @property integer $category_item_id
 * @property integer $product_id
 * @property string $description
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProductItem whereCategoryItemId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProductItem whereProductId($value)
 */

class ProductItem extends Model
{
    public $table = 'product_item';
    public $timestamps = false;
}
