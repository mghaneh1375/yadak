<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'ConfigModel'
 *
 * @property integer $id
 * @property integer $critical_threshold
 * @property integer $warning_threshold
 */

class ConfigModel extends Model
{
    public $table = 'config';
    public $timestamps = false;

    public static function whereId($value) {
        return ConfigModel::find($value);
    }
}
