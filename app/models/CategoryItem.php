<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'CategoryItem'
 *
 * @property integer $id
 * @property string $name
 * @property integer $category_id
 * @property integer $base_item_id
 * @method static \Illuminate\Database\Query\Builder|\App\models\CategoryItem whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\CategoryItem whereCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\CategoryItem whereBaseItemId($value)
 */

class CategoryItem extends Model
{
    public $table = 'category_item';
    public $timestamps = false;

    public static function whereId($value) {
        return CategoryItem::find($value);
    }
}
