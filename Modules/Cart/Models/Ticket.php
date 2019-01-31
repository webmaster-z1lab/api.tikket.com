<?php

namespace Modules\Cart\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Ticket extends Model
{
    public $timestamps = FALSE;

    protected $fillable = ['entrance_id', 'entrance', 'lot', 'price', 'name', 'document', 'email'];

    protected $casts = ['price' => 'integer'];
}
