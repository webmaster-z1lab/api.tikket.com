<?php

namespace Modules\Event\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Producer
 *
 * @package Modules\Event\Models
 *
 * @property string user_id
 * @property string name
 * @property string description
 */
class Producer extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
    ];
}
