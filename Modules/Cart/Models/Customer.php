<?php

namespace Modules\Cart\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Customer
 *
 * @package Modules\Cart\Models
 *
 * @property string document
 * @property \Modules\Cart\Models\Phone phone
 */
class Customer extends Model
{
    public $timestamps = FALSE;

    protected $fillable = [
        'document'
    ];

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function phone()
    {
        return $this->embedsOne(Phone::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function address()
    {
        return $this->embedsOne(Address::class);
    }
}
