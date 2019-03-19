<?php

namespace Modules\Report\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['total', 'last_days'];

    protected $casts = [
        'total'     => 'integer',
        'last_days' => 'array',
    ];
}
