<?php

namespace Modules\Cart\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Modules\Cart\Scopes\NotExpiredScope;
use Modules\Event\Models\Event;

/**
 * Class Cart
 *
 * @package Modules\Cart\Models
 *
 * @property string                        user_id
 * @property string                        type
 * @property string                        hash
 * @property string                        callback
 * @property integer                       amount
 * @property integer                       fee
 * @property integer                       fee_percentage
 * @property boolean                       fee_is_hidden
 * @property \Carbon\Carbon                expires_at
 * @property \Modules\Event\Models\Event   event
 * @property \Modules\Cart\Models\Ticket   tickets
 * @property \Modules\Cart\Models\Card     card
 * @property \Modules\Cart\Models\Costumer costumer
 * @property-read \Carbon\Carbon           created_at
 * @property-read \Carbon\Carbon           updated_at
 */
class Cart extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'type',
        'hash',
        'callback',
        'amount',
        'fee',
        'fee_percentage',
        'fee_is_hidden',
        'expires_at',
    ];

    protected $dates = ['expires_at'];

    protected $casts = [
        'fee_is_hidden'  => 'boolean',
        'fee_percentage' => 'integer',
        'amount'         => 'integer',
        'fee'            => 'integer',
    ];

    protected $attributes = [
        'type'   => 'credit_card',
        'amount' => 0,
        'fee'    => 0,
    ];

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
    public function costumer()
    {
        return $this->embedsOne(Costumer::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new NotExpiredScope());
    }
}
