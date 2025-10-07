<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Basket'
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $payment_kind
 * @property integer $follow_code
 * @property boolean $confirm
 * @property boolean $decease_from_warehouse
 * @property boolean $reject
 * @property boolean $validate_offcode
 * @property string $created_at
 * @property string $offcode
 * @property string $confirm_date
 * @property string $pic
 * @property string $description
 * @property string $products
 * @property string $arrival_date
 * @method static \Illuminate\Database\Query\Builder|\App\models\Basket whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\Basket whereConfirm($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\Basket whereReject($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\Basket whereFollowCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\Basket wherePaymentKind($value)
 */

class Basket extends Model {

    public $table = 'basket';
    // public $timestamps = false;

    public static function whereId($value) {
        return Basket::find($value);
    }
}
