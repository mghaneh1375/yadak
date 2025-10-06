<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'FAQCategory'
 *
 * @property integer $id
 * @property string $name
 */

class FAQCategory extends Model {

    public $table = 'faq_category';
    public $timestamps = false;

    public static function whereId($value) {
        return FAQCategory::find($value);
    }
}
