<?php

namespace Modules\Event\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Lot
 *
 * @package Modules\Event\Models
 *
 * @property int            amount
 * @property int            value
 * @property \Carbon\Carbon finishes_at
 */
class Lot extends Model
{
    protected $fillable = [
        'number',
        'amount',
        'value',
        'fee',
        'finishes_at',
        'starts_at',
    ];

    protected $casts = [
        'number' => 'integer',
        'amount' => 'integer',
        'value'  => 'integer',
        'fee'    => 'integer',
    ];

    protected $dates = [
        'finishes_at',
    ];

    /**
     * @return int
     */
    public function getPriceAttribute()
    {
        return (int)$this->attributes['value'] + (int)$this->attributes['fee'];
    }
}
