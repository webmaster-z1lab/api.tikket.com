<?php

namespace Modules\Ticket\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Event
 *
 * @package Modules\Ticket\Models
 *
 * @property string event_id
 * @property string name
 * @property string cover
 * @property string local
 * @property string starts_at
 */
class Event extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'url',
        'cover',
        'address',
        'address_url',
        'starts_at',
    ];
}
