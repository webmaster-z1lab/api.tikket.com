<?php

namespace Modules\Ticket\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Event
 *
 * @package Modules\Ticket\Models
 *
 * @property string                       event_id
 * @property string                       name
 * @property string                       url
 * @property string                       address
 * @property \Carbon\Carbon               starts_at
 * @property \Modules\Ticket\Models\Image image
 */
class Event extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'url',
        'address',
        'starts_at',
    ];

    protected $dates = ['starts_at'];

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function image()
    {
        return $this->embedsOne(Image::class);
    }
}
