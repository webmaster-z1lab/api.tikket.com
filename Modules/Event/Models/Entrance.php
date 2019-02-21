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
 * @property int                                      min_buy
 * @property int                                      max_buy
 * @property \Carbon\Carbon                           starts_at
 * @property \Modules\Event\Models\Available          available
 * @property \Illuminate\Database\Eloquent\Collection lots
 * @property-read \Carbon\Carbon                      created_at
 * @property-read \Carbon\Carbon                      updated_at
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
    ];

    protected $dates = [
        'starts_at',
    ];

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
}
