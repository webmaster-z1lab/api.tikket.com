<?php

namespace Modules\Order\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Item extends Model
{
    public $timestamps = FALSE;

    protected $fillable = ['item_id', 'description', 'quantity', 'amount'];

    protected $casts = [
        'quantity' => 'integer',
        'amount'   => 'integer',
    ];
}
