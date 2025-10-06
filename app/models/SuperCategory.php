<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'SuperCategory'
 *
 * @property integer $id
 * @property string $name
 * @method static \Illuminate\Database\Query\Builder|\App\models\SuperCategory whereName($value)
 */

class SuperCategory extends Model
{
    public $table = 'super_category';
    public $timestamps = false;

    public static function whereId($value) {
        return SuperCategory::find($value);
    }
}
