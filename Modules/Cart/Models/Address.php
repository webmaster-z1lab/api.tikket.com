<?php

namespace Modules\Cart\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Address
 *
 * @package Modules\Cart\Models
 *
 * @property string  street
 * @property string  complement
 * @property string  district
 * @property string  postal_code
 * @property string  city
 * @property string  state
 * @property integer number
 */
class Address extends Model
{
    public $timestamps = FALSE;

    protected $fillable = [
        'street',
        'number',
        'complement',
        'district',
        'postal_code',
        'city',
        'state',
    ];

    protected $attributes = [
        'complement' => NULL,
    ];

    protected $casts = [
        'number' => 'integer',
    ];
}
