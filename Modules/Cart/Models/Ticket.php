<?php

namespace Modules\Cart\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Ticket
 *
 * @package Modules\Cart\Models
 *
 * @property string       entrance_id
 * @property string       entrance
 * @property string       lot
 * @property string       name
 * @property string       document
 * @property string       email
 * @property integer      value
 * @property integer      fee
 * @property-read integer price
 */
class Ticket extends Model
{
    public $timestamps = FALSE;

    protected $fillable = [
        'entrance_id',
        'entrance',
        'lot',
        'value',
        'fee',
        'name',
        'document',
        'email',
    ];

    protected $casts = [
        'price' => 'integer',
        'value' => 'integer',
        'fee'   => 'integer',
    ];

    /**
     * @return integer
     */
    public function getPriceAttribute()
    {
        return $this->attributes['value'] + $this->attributes['fee'];
    }
}
