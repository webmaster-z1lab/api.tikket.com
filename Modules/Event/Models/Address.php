<?php

namespace Modules\Event\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Address
 *
 * @package Modules\Event\Models
 *
 * @property string                           name
 * @property string                           street
 * @property string                           number
 * @property string                           district
 * @property string                           complement
 * @property string                           city
 * @property string                           state
 * @property string                           postal_code
 * @property string                           formatted
 * @property string                           maps_url
 * @property \Modules\Event\Models\Coordinate coordinate
 *
 */
class Address extends Model
{
    public $timestamps = FALSE;

    protected $fillable = [
        'name',
        'street',
        'number',
        'district',
        'complement',
        'city',
        'state',
        'postal_code',
        'formatted',
        'maps_url',
    ];

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function coordinate()
    {
        return $this->embedsOne(Coordinate::class);
    }
}
