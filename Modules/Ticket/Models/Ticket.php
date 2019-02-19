<?php

namespace Modules\Ticket\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Modules\Event\Models\Entrance;
use Modules\Order\Models\Order;

/**
 * Class Ticket
 *
 * @package Modules\Ticket\Models
 *
 * @property string                             order_id
 * @property string                             entrance_id
 * @property string                             name
 * @property string                             lot
 * @property string                             code
 * @property string                             status
 * @property \Modules\Ticket\Models\Participant participant
 * @property \Modules\Ticket\Models\Event       event
 * @property \Modules\Order\Models\Order        order
 * @property \Modules\Event\Models\Entrance     entrance
 * @property-read \Carbon\Carbon                created_at
 * @property-read \Carbon\Carbon                updated_at
 */
class Ticket extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'lot',
        'code',
        'status',
    ];

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function participant()
    {
        return $this->embedsOne(Participant::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function event()
    {
        return $this->embedsOne(Event::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entrance()
    {
        return $this->belongsTo(Entrance::class);
    }
}
