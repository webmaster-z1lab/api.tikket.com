<?php

namespace Modules\Cart\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Phone
 *
 * @package Modules\Cart\Models
 *
 * @property string area_code
 * @property string phone
 */
class Phone extends Model
{
    public $timestamps = FALSE;

    protected $fillable = [
        'area_code',
        'phone',
    ];
}
