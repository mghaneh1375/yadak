<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Offer'
 *
 * @property integer $id
 * @property integer $kind
 * @property integer $amount
 * @property string $expire
 * @property string $code
 * @method static \Illuminate\Database\Query\Builder|\App\models\Offer whereKind($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\Offer whereCode($value)
 */

class Offer extends Model {

    public $table = 'offer';
    public $timestamps = false;

    public static function whereId($value) {
        return Offer::find($value);
    }
}
