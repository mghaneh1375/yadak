<?php

namespace App\models;

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
 * @method static \Illuminate\Database\Query\Builder|\App\models\Product whereHide($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\Product whereCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\Product whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\Product whereMoney($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\Product whereOffer($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\Product whereNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\Product whereBrandId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\Product whereSecondaryPrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\Product whereBest($value)
 */

class Product extends Model
{
    public $table = 'product';

    public static function whereId($value) {
        return Product::find($value);
    }
}
