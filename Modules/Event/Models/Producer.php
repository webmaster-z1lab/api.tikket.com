<?php

namespace Modules\Event\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Producer
 *
 * @package Modules\Event\Models
 *
 * @property string name
 * @property string description
 */
class Producer extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];
}
