<?php

namespace Modules\Event\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Available
 *
 * @package Modules\Event\Models
 *
 * @property integer             lot
 * @property string              lot_id
 * @property integer             available
 * @property integer             reserved
 * @property integer             waiting
 * @property integer             sold
 * @property integer             amount
 * @property integer             remainder
 * @property integer             value
 * @property integer             fee
 * @property integer             price
 * @property boolean             is_sold_out
 * @property boolean             is_active
 * @property \Carbon\Carbon      starts_at
 * @property \Carbon\Carbon      finishes_at
 * @property-read \Carbon\Carbon created_at
 * @property-read \Carbon\Carbon updated_at
 */
class Available extends Model
{
    const STATUS_ACTIVE = TRUE;
    const STATUS_SOLD_OUT = FALSE;
    const QNT_RESERVED = 0;
    const QNT_WAITING = 0;
    const QNT_SOLD = 0;
    const QNT_AMOUNT = 0;
    const QNT_REMAINDER = 0;

    protected $fillable = [
        'lot_id',
        'lot',
        'available',
        'reserved',
        'waiting',
        'sold',
        'amount',
        'remainder',
        'value',
        'fee',
        'price',
        'is_sold_out',
        'is_active',
        'starts_at',
        'finishes_at',
    ];

    protected $attributes = [
        'sold'        => self::QNT_SOLD,
        'amount'      => self::QNT_AMOUNT,
        'waiting'     => self::QNT_WAITING,
        'reserved'    => self::QNT_RESERVED,
        'remainder'   => self::QNT_REMAINDER,
        'is_active'   => self::STATUS_ACTIVE,
        'is_sold_out' => self::STATUS_SOLD_OUT,
    ];

    protected $casts = [
        'available' => 'integer',
        'amount'    => 'integer',
        'remainder' => 'integer',
        'reserved'  => 'integer',
        'waiting'   => 'integer',
        'sold'      => 'integer',
        'value'     => 'integer',
        'fee'       => 'integer',
        'price'     => 'integer',
        'is_active' => 'boolean',
        'lot'       => 'integer',
    ];

    protected $dates = [
        'starts_at',
        'finishes_at',
    ];

    /**
     * @return bool
     */
    public function isExpired()
    {
        return !now()->between($this->attributes['starts_at'], $this->attributes['finishes_at']);
    }

    /**
     * @return bool
     */
    public function isSoldOut()
    {
        return $this->attributes['amount'] === $this->attributes['sold'];
    }
}
