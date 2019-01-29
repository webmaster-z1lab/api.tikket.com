<?php

namespace Modules\Order\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Address extends Model
{
    public $timestamps = FALSE;

    protected $fillable = ['street', 'number', 'complement', 'district', 'postal_code', 'city', 'state'];

    protected $attributes = ['complement' => NULL];

    protected $casts = ['number' => 'integer'];
}
