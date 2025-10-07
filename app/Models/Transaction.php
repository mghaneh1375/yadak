<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Transaction'
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $takhfif
 * @property integer $takhfif_amount
 * @property integer $product_id
 * @property integer $status
 * @property integer $follow_code
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Transaction whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Transaction whereProductId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Transaction whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Transaction whereFollowCode($value)
 */

class Transaction extends Model {

    public $table = 'transactions';

    public static function whereId($value) {
        return Transaction::find($value);
    }
}
