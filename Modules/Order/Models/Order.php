<?php

namespace Modules\Order\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Modules\Event\Models\Event;

/**
 * Class Order
 *
 * @package Modules\Order\Models
 *
 * @property string                         status
 * @property string                         amount
 * @property string                         fee
 * @property string                         hash
 * @property string                         ip
 * @property string                         type
 * @property integer                        fee_percentage
 * @property boolean                        fee_is_hidden
 * @property \Modules\Order\Models\Costumer costumer
 * @property \Modules\Order\Models\Card     card
 * @property \Modules\Order\Models\Ticket   tickets
 * @property \Modules\Event\Models\Event    event
 * @property-read \Carbon\Carbon            created_at
 * @property-read \Carbon\Carbon            updated_at
 */
class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'status',
        'amount',
        'fee',
        'fee_percentage',
        'fee_is_hidden',
        'hash',
        'ip',
        'type',
    ];

    protected $casts = [
        'amount'         => 'integer',
        'fee'            => 'integer',
        'fee_percentage' => 'integer',
        'fee_is_hidden'  => 'boolean',
    ];

    protected $attributes = [
        'status' => 'waiting',
    ];

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
}
