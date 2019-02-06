<?php

namespace Modules\Cart\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Ticket extends Model
{
    public $timestamps = FALSE;

    protected $fillable = [
        'entrance_id',
        'entrance',
        'lot',
        'price',
        'fee',
        'name',
        'document',
        'email',
    ];

    protected $casts = [
        'price' => 'integer',
        'fee'   => 'integer',
    ];

    /*public function getPriceAttribute()
    {
        return ($this->attributes['value'] + $this->attributes['fee']);
    }*/
}
