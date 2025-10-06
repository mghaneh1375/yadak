<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Activation'
 *
 * @property integer $id
 * @property integer $code
 * @property string $send_time
 * @property string $phone
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Query\Builder|\App\models\Activation wherePhone($value)
 */

class Activation extends Model {

    protected $table = 'activation';
    public $timestamps = false;

}