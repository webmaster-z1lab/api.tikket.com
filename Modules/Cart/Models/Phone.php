<?php

namespace Modules\Cart\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Phone extends Model
{
    public $timestamps = FALSE;

    protected $fillable = ['area_code', 'phone'];
}
