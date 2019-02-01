<?php

namespace Modules\Order\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Ticket extends Model
{
    public $timestamps = FALSE;

    protected $fillable = ['entrance_id', 'entrance', 'lot', 'price', 'fee', 'name', 'document', 'email'];

    protected $casts = [
        'price' => 'integer',
        'fee'   => 'integer',
    ];
}
