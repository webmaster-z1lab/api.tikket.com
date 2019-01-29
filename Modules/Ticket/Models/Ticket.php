<?php

namespace Modules\Ticket\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

/**
 * Class Ticket
 *
 * @package Modules\Ticket\Models
 *
 * @property string                             order_id
 * @property string                             name
 * @property string                             lot
 * @property string                             barcode
 * @property string                             status
 * @property \Modules\Ticket\Models\Participant participant
 * @property \Modules\Ticket\Models\Event       event
 * @property-read \Carbon\Carbon created_at
 * @property-read \Carbon\Carbon updated_at
 */
class Ticket extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_id',
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
}
