<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'ProductPic'
 *
 * @property integer $id
 * @property integer $product_id
 * @property string $name
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProductPic whereProductId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProductPic whereName($value)
 */

class ProductPic extends Model
{
    public $table = 'product_pic';
    public $timestamps = false;
}
