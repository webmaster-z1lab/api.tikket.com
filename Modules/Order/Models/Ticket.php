<?php

namespace Modules\Order\Models;

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
        'code'
    ];

    protected $casts = [
        'value' => 'integer',
        'price' => 'integer',
        'fee'   => 'integer',
    ];

    /**
     * @return integer
     */
    public function getPriceAttribute()
    {
        return $this->attributes['value'];
    }
}
