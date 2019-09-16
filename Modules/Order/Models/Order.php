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
 * @property string                         id
 * @property string                         event_id
 * @property string                         transaction_id
 * @property string                         status
 * @property integer                        amount
 * @property integer                        fee
 * @property string                         hash
 * @property string                         ip
 * @property string                         type
 * @property string                         channel
 * @property string                         code
 * @property integer                        discount
 * @property integer                        fee_percentage
 * @property boolean                        fee_is_hidden
 * @property \Modules\Order\Models\Customer customer
 * @property \Modules\Order\Models\Card     card
 * @property \Modules\Order\Models\Boleto   boleto
 * @property \Modules\Event\Models\Coupon   coupon
 * @property \Modules\Order\Models\SalePoint sale_point
 * @property \Modules\Order\Models\SalePoint administrator
 * @property \Illuminate\Database\Eloquent\Collection bags
 * @property \Illuminate\Database\Eloquent\Collection tickets
 * @property \Modules\Ticket\Models\Ticket actual_tickets
 * @property \Modules\Event\Models\Event event
 * @property-read \Carbon\Carbon created_at
 * @property-read \Carbon\Carbon updated_at
 *
 * @method $this paid()
 * @method $this processed()
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
    public const PDV_CHANNEL    = 'pdv';
    public const ADMIN_CHANNEL  = 'admin';

    public const CODE_LENGTH = 8;

    protected $fillable = [
        'code',
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
        'status'   => 'waiting',
        'discount' => 0,
    ];

    /**
     * @param $query
     *
     * @return \Jenssegers\Mongodb\Eloquent\Builder
     */
    public function scopePaid($query): \Jenssegers\Mongodb\Eloquent\Builder
    {
        return $query->where('status', self::PAID);
    }

    /**
     * @param $query
     *
     * @return \Jenssegers\Mongodb\Eloquent\Builder
     */
    public function scopeProcessed($query): \Jenssegers\Mongodb\Eloquent\Builder
    {
        return $query->where('status', self::PAID)->orWhere('status', self::WAITING);
    }

    /**
     * @param        $query
     * @param  string  $document
     *
     * @return \Jenssegers\Mongodb\Eloquent\Builder
     */
    public function scopeByPerson($query, $document): \Jenssegers\Mongodb\Eloquent\Builder
    {
        return $query->where('customer.document', $document);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function customer(): \Jenssegers\Mongodb\Relations\EmbedsOne
    {
        return $this->embedsOne(Customer::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function card(): \Jenssegers\Mongodb\Relations\EmbedsOne
    {
        return $this->embedsOne(Card::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function boleto(): \Jenssegers\Mongodb\Relations\EmbedsOne
    {
        return $this->embedsOne(Boleto::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsMany
     */
    public function bags(): \Jenssegers\Mongodb\Relations\EmbedsMany
    {
        return $this->embedsMany(Bag::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsMany
     */
    public function tickets(): \Jenssegers\Mongodb\Relations\EmbedsMany
    {
        return $this->embedsMany(Ticket::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coupon(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function sale_point(): \Jenssegers\Mongodb\Relations\EmbedsOne
    {
        return $this->embedsOne(SalePoint::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function administrator(): \Jenssegers\Mongodb\Relations\EmbedsOne
    {
        return $this->embedsOne(SalePoint::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actual_tickets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Ticket\Models\Ticket::class);
    }
}
