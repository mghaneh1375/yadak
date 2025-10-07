<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Brand'
 *
 * @property integer $id
 * @property string $name
 * @property integer $category_id
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Brand whereCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Brand whereName($value)
 */

class Brand extends Model
{
    public $table = 'brand';
    public $timestamps = false;

    public static function whereId($value) {
        return Brand::find($value);
    }
}
