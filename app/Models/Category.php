<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * An Eloquent Model: 'Category'
 *
 * @property integer $id
 * @property integer $super_category_id
 * @property string $name
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Category whereSuperCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Category whereName($value)
 */

class Category extends Model
{
    public $table = 'category';
    public $timestamps = false;

    public static function whereId($value) {
        return Category::find($value);
    }
}
