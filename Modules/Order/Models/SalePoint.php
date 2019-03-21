<?php

namespace Modules\Order\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class SalePoint extends Model
{
    public $timestamps = FALSE;

    protected $fillable = ['user_id', 'name', 'document', 'email'];
}
