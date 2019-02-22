<?php

namespace Modules\Event\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

/**
 * Class Entrance
 *
 * @package Modules\Event\Models
 *
 * @property string                                   name
 * @property bool                                     is_free
 * @property bool                                     is_locked
 * @property int                                      min_buy
 * @property int                                      max_buy
 * @property \Carbon\Carbon                           starts_at
 * @property \Modules\Event\Models\Event              event
 * @property \Modules\Event\Models\Available          available
 * @property \Illuminate\Database\Eloquent\Collection lots
 * @property-read \Carbon\Carbon                      created_at
 * @property-read \Carbon\Carbon                      updated_at
 *
 * @method $this expired()
 * @method $this soldOut()
 */
class Entrance extends Model
{
    use SoftDeletes;

    /**
     * Entrances available for sell
     */
    public const AVAILABLE = 'available';
    /**
     * Entrances in orders waiting for payment
     */
    public const WAITING = 'waiting';
    /**
     * Entrances in opened carts
     */
    public const RESERVED = 'reserved';
    /**
     * Entrances sold
     */
    public const SOLD = 'sold';
    /**
     * Entrances offered for sell
     */
    public const AMOUNT = 'amount';

    const STATUS_PUBLIC = TRUE;
    const STATUS_FREE = FALSE;
    const MIN_BUY = 1;
    const MAX_BUY = 5;

    protected $attributes = [
        'is_free' => self::STATUS_PUBLIC,
        'min_buy' => self::MIN_BUY,
        'max_buy' => self::MAX_BUY,
    ];

    protected $fillable = [
        'name',
        'is_free',
        'min_buy',
        'max_buy',
        'starts_at',
        'description',
        'is_locked',
    ];

    protected $dates = [
        'starts_at',
    ];

    public function scopeExpired($query)
    {
        return $query->where('available.finishes_at', '<', now());
    }

    public function scopeSoldOut($query)
    {
        return $query->where('available.is_sold_out', TRUE);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsMany
     */
    public function lots()
    {
        return $this->embedsMany(Lot::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function available()
    {
        return $this->embedsOne(Available::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @return \Modules\Event\Models\Lot
     */
    public function firstLot()
    {
        return $this->lots->firstWhere('number', 1);
    }

    /**
     * @param int $number
     *
     * @return \Modules\Event\Models\Lot
     */
    public function getLot(int $number)
    {
        return $this->lots->firstWhere('number', $number);
    }
}
