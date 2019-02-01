<?php

namespace Modules\Order\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Ticket extends Model
{
    public $timestamps = FALSE;

    protected $fillable = ['entrance_id', 'lot', 'name', 'document', 'email'];
}