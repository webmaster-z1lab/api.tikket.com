<?php

namespace Modules\Order\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Bag
 *
 * @package Modules\Order\Models
 *
 * @property string         id
 * @property string         url
 * @property string         barcode
 * @property \Carbon\Carbon due_at
 */
class Boleto extends Model
{
    public $timestamps = FALSE;

    protected $fillable = [
        'url',
        'barcode',
        'due_at',
    ];

    protected $dates = ['due_at'];
}
