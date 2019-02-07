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
 * @property-read  \Carbon\Carbon created_at
 * @property-read  \Carbon\Carbon updated_at
 */
class Producer extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
    ];
}
