<?php

namespace Modules\Cart\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Modules\Event\Models\Coupon;
use Modules\Event\Models\Event;

/**
 * Class Cart
 *
 * @package Modules\Cart\Models
 *
 * @property string                                   user_id
 * @property string                                   type
 * @property string                                   hash
 * @property string                                   callback
 * @property string                                   status
 * @property integer                                  amount
 * @property integer                                  discount
 * @property integer                                  fee
 * @property integer                                  fee_percentage
 * @property boolean                                  fee_is_hidden
 * @property \Carbon\Carbon                           expires_at
 * @property \Modules\Event\Models\Event              event
 * @property \Illuminate\Database\Eloquent\Collection bags
 * @property \Illuminate\Database\Eloquent\Collection tickets
 * @property \Modules\Cart\Models\Card                card
 * @property \Modules\Cart\Models\Customer            customer
 * @property \Modules\Event\Models\Coupon             coupon
 * @property-read \Carbon\Carbon                      created_at
 * @property-read \Carbon\Carbon                      updated_at
 * @method $this active()
 */
class Cart extends Model
{
    use SoftDeletes;

    public const OPENED = 'opened';
    public const FINISHED = 'finished';
    public const RECYCLED = 'recycled';

    protected $fillable = [
        'user_id',
        'type',
        'hash',
        'callback',
        'amount',
        'discount',
        'fee',
        'status',
        'fee_percentage',
        'fee_is_hidden',
        'expires_at',
        'is_free',
    ];

    protected $dates = ['expires_at'];

    protected $casts = [
        'fee_is_hidden'  => 'boolean',
        'fee_percentage' => 'integer',
        'amount'         => 'integer',
        'discount'       => 'integer',
        'fee'            => 'integer',
        'is_free'        => 'boolean',
    ];

    protected $attributes = [
        'status'   => self::OPENED,
        'amount'   => 0,
        'fee'      => 0,
        'discount' => 0,
        'is_free'  => TRUE,
    ];

    public function scopeActive($query)
    {
        return $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsMany
     */
    public function bags()
    {
        return $this->embedsMany(Bag::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsMany
     */
    public function tickets()
    {
        return $this->embedsMany(Ticket::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function card()
    {
        return $this->embedsOne(Card::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function customer()
    {
        return $this->embedsOne(Customer::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
