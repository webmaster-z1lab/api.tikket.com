<?php

namespace Modules\Event\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Lot
 *
 * @package Modules\Event\Models
 *
 * @property int            amount
 * @property int            value
 * @property \Carbon\Carbon starts_at
 * @property \Carbon\Carbon finishes_at
 */
class Lot extends Model
{
    protected $fillable = [
        'amount',
        'value',
        'starts_at',
        'finishes_at',
    ];

    protected $casts = [
        'amount' => 'integer',
        'value'  => 'integer',
    ];

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int                                   $position
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNumber($query, $position)
    {
        return $query->orderBy('starts_at')->skip($position - 1);
    }
}
