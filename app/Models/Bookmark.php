<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * An Eloquent Model: 'Bookmark'
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $user_id
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Bookmark whereProductId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Bookmark whereUserId($value)
 */

class Bookmark extends Model {

    public $table = 'bookmarks';
    public $timestamps = false;

    public static function whereId($value) {
        return Bookmark::find($value);
    }
}
