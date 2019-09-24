<?php

namespace Modules\Order\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Customer
 *
 * @package Modules\Order\Models
 *
 * @property string                        id
 * @property string                        user_id
 * @property string                        name
 * @property string                        email
 * @property string                        document
 * @property \Modules\Order\Models\Phone   phone
 * @property \Modules\Order\Models\Address address
 */
class Customer extends Model
{
    public $timestamps = FALSE;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'document',
    ];

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function phone(): \Jenssegers\Mongodb\Relations\EmbedsOne
    {
        return $this->embedsOne(Phone::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function address(): \Jenssegers\Mongodb\Relations\EmbedsOne
    {
        return $this->embedsOne(Address::class);
    }
}
