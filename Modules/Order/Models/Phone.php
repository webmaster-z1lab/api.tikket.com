<?php

namespace Modules\Order\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Phone extends Model
{
    public $timestamps = FALSE;

    protected $fillable = ['area_code', 'phone'];
}
