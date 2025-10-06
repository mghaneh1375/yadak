<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'CommonQuestion'
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $question
 * @property string $answer
 * @method static \Illuminate\Database\Query\Builder|\App\models\CommonQuestion whereCategoryId($value)
 */

class CommonQuestion extends Model {

    public $table = 'common_question';
    public $timestamps = false;

    public static function whereId($value) {
        return CommonQuestion::find($value);
    }
}
