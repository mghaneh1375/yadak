<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'SlideBar'
 *
 * @property integer $id
 * @property string $pic
 * @method static \Illuminate\Database\Query\Builder|\App\models\SlideBar wherePic($value)
 */

class SlideBar extends Model
{
    public $table = 'slidebar';
    public $timestamps = false;

    public static function whereId($value) {
        return SlideBar::find($value);
    }
}
