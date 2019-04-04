<?php

namespace Modules\Order\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Bag
 *
 * @package Modules\Order\Models
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
