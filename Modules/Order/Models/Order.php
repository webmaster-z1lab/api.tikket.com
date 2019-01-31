<?php

namespace Modules\Order\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = ['status', 'amount', 'fee', 'hash', 'ip', 'type'];

    protected $casts = [
        'amount' => 'integer',
        'fee'    => 'integer',
    ];

    protected $attributes = ['status' => 'waiting'];

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
