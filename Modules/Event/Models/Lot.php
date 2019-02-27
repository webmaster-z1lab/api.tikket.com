<?php

namespace Modules\Event\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Lot
 *
 * @package Modules\Event\Models
 *
 * @property integer        number
 * @property int            amount
 * @property int            value
 * @property integer        fee
 * @property integer        price
 * @property string         status
 * @property \Carbon\Carbon starts_at
 * @property \Carbon\Carbon finishes_at
 */
class Lot extends Model
{
    /**
     * Lot closed for exceeding the deadline or available vacancies
     */
    public const CLOSED = 'closed';
    /**
     * Lot not yet started or no sale made
     */
    public const OPEN = 'open';
    /**
     * Lot partially blocked because you have already started sales
     */
    public const LOCKED = 'locked';

    public const MIN_FEE = 400;

    protected $fillable = [
        'number',
        'amount',
        'value',
        'fee',
        'status',
        'finishes_at',
        'starts_at',
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
    ];

    /**
     * @return int
     */
    public function getPriceAttribute()
    {
        return (int)$this->attributes['value'] + (int)$this->attributes['fee'];
    }
}
