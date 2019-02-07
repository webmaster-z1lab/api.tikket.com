<?php

namespace Modules\Order\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Holder
 *
 * @package Modules\Order\Models
 *
 * @property string                       name
 * @property string                       document
 * @property \Carbon\Carbon               birth_date
 * @property \Modules\Order\Models\Address address
 * @property \Modules\Order\Models\Phone   phone
 */
class Holder extends Model
{
    public $timestamps = FALSE;

    protected $fillable = [
        'name',
        'document',
        'birth_date',
    ];

    protected $dates = [
        'birth_date',
    ];

    protected $dateFormat = 'Y-m-d';

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function address()
    {
        return $this->embedsOne(Address::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function phone()
    {
        return $this->embedsOne(Phone::class);
    }
}
