<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Product'
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $name
 * @property string $desc
 * @property integer $money
 * @property integer $secondary_price
 * @property integer $offer
 * @property integer $number
 * @property integer $brand_id
 * @property integer $best
 * @property boolean $hide
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereHide($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereMoney($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereOffer($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereBrandId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereSecondaryPrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereBest($value)
 */

class Product extends Model
{
    public $table = 'product';

    public static function whereId($value) {
        return Product::find($value);
    }
}
