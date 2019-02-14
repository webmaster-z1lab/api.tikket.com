<?php

namespace Modules\Cart\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Bag
 *
 * @package Modules\Cart\Models
 *
 * @property string  entrance_id
 * @property integer amount
 */
class Bag extends Model
{
    public $timestamps = FALSE;

    protected $casts = [
        'amount' => 'integer',
    ];

    protected $fillable = [
        'entrance_id',
        'amount',
    ];
}
