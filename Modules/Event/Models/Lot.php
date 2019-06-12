<?php

namespace Modules\Event\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Lot
 *
 * @package Modules\Event\Models
 *
 * @property string         _id
 * @property integer        number
 * @property int            amount
 * @property int            available
 * @property int            reserved
 * @property int            waiting
 * @property int            sold
 * @property int            value
 * @property integer        fee
 * @property integer        price
 * @property string         status
 * @property \Carbon\Carbon starts_at
 * @property \Carbon\Carbon finishes_at
 * @property \Carbon\Carbon changed_at
 */
class Lot extends Model
{
    /**
     * Lot closed for exceeding the available vacancies
     */
    public const CLOSED = 'closed';
    /**
     * Lot closed for exceeding the deadline
     */
    public const EXPIRED = 'expired';
    /**
     * Lot not yet started or no sale made
     */
    public const OPEN = 'open';
    /**
     * Lot partially blocked because you have already started sales
     */
    public const LOCKED = 'locked';

    protected $fillable = [
        'number',
        'amount',
        'available',
        'reserved',
        'waiting',
        'sold',
        'value',
        'fee',
        'status',
        'finishes_at',
        'starts_at',
        'changed_at',
    ];

    protected $casts = [
        'number' => 'integer',
        'amount' => 'integer',
        'value'  => 'integer',
        'fee'    => 'integer',
    ];

    protected $attributes = [
        'status' => self::OPEN,
    ];

    protected $dates = [
        'starts_at',
        'finishes_at',
        'changed_at',
    ];

    /**
     * @return int
     */
    public function getPriceAttribute()
    {
        return (int)$this->attributes['value'];
    }

    /**
     * @param $value
     */
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value;
        $this->attributes['changed_at'] = now();
    }
}
