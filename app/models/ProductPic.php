<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'ProductPic'
 *
 * @property integer $id
 * @property integer $product_id
 * @property string $name
 * @method static \Illuminate\Database\Query\Builder|\App\models\ProductPic whereProductId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\ProductPic whereName($value)
 */

class ProductPic extends Model
{
    public $table = 'product_pic';
    public $timestamps = false;
}
