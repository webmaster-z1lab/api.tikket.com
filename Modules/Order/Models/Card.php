<?php

namespace Modules\Order\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Card
 *
 * @package Modules\Order\Models
 *
 * @property string brand
 * @property string number
 * @property string token
 * @property integer installments
 * @property integer parcel
 * @property \Modules\Cart\Models\Holder holder
 */
class Card extends Model
{
    public $timestamps = FALSE;

    protected $fillable = [
        'brand',
        'number',
        'token',
        'installments',
        'parcel',
    ];

    protected $casts = [
        'installments' => 'integer',
        'parcel'       => 'integer',
    ];

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function holder()
    {
        return $this->embedsOne(Holder::class);
    }
}
