<?php

namespace Modules\Order\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Boleto extends Model
{
    public $timestamps = FALSE;

    protected $fillable = ['url', 'barcode', 'due_date'];

    protected $dates = ['due_date'];
}
