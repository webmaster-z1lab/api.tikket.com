<?php

namespace Modules\Order\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Modules\Event\Models\Coupon;
use Modules\Event\Models\Event;

/**
 * Class Order
 *
 * @package Modules\Order\Models
 *
 * @property string                                   status
 * @property string                                   amount
 * @property string                                   fee
 * @property string                                   hash
 * @property string                                   ip
 * @property string                                   type
 * @property string                                   channel
 * @property integer                                  fee_percentage
 * @property boolean                                  fee_is_hidden
 * @property \Modules\Order\Models\Costumer           costumer
 * @property \Modules\Order\Models\Card               card
 * @property \Illuminate\Database\Eloquent\Collection bags
 * @property \Illuminate\Database\Eloquent\Collection tickets
 * @property \Modules\Event\Models\Event              event
 * @property-read \Carbon\Carbon                      created_at
 * @property-read \Carbon\Carbon                      updated_at
 *
 * @method $this paid()
 * @method $this byPerson(string $document)
 */
class Order extends Model
{
    use SoftDeletes;

    /**
     * Order waiting for cardholder approval
     */
    public const WAITING = 'waiting';
    /**
     * Order payment approved
     */
    public const PAID = 'paid';
    /**
     * Order canceled by user or cardholder
     */
    public const CANCELED = 'canceled';
    /**
     * Order reversed to buyer
     */
    public const REVERSED = 'reversed';

    public const ONLINE_CHANNEL = 'online';
    public const PDV_CHANNEL = 'pdv';
    public const ADMIN_CHANNEL = 'admin';

    protected $fillable = [
        'status',
        'amount',
        'discount',
        'fee',
        'fee_percentage',
        'fee_is_hidden',
        'hash',
        'ip',
        'type',
        'channel',
    ];

    protected $casts = [
        'amount'         => 'integer',
        'discount'       => 'integer',
        'fee'            => 'integer',
        'fee_percentage' => 'integer',
        'fee_is_hidden'  => 'boolean',
    ];

    protected $attributes = [
        'status' => 'waiting',
    ];

    /**
     * @param $query
     *
     * @return $this
     */
    public function scopePaid($query)
    {
        return $query->where('status', self::PAID);
    }

    /**
     * @param        $query
     * @param string $document
     *
     * @return $this
     */
    public function scopeByPerson($query, $document)
    {
        return $query->where('costumer.document', $document);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function costumer()
    {
        return $this->embedsOne(Costumer::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function card()
    {
        return $this->embedsOne(Card::class);
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function sale_point()
    {
        return $this->embedsOne(SalePoint::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function administrator()
    {
        return $this->embedsOne(SalePoint::class);
    }
}
