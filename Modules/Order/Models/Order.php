<?php

namespace Modules\Order\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

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
 * @property \Modules\Order\Models\Costumer costumer
 * @property \Modules\Order\Models\Card     card
 * @property \Modules\Order\Models\Ticket   tickets
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
        'hash',
        'ip',
        'type',
    ];

    protected $casts = [
        'amount' => 'integer',
        'fee'    => 'integer',
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
}
