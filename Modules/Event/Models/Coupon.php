<?php

namespace Modules\Event\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

/**
 * Class Coupon
 *
 * @package Modules\Event\Models
 *
 * @property string                         name
 * @property boolean                        is_percentage
 * @property \Carbon\Carbon                 valid_until
 * @property string                         code
 * @property integer                        discount
 * @property integer                        quantity
 * @property \Modules\Event\Models\Event    event
 * @property \Modules\Event\Models\Entrance entrance
 * @property \Carbon\Carbon                 created_at
 * @property \Carbon\Carbon                 updated_at
 */
class Coupon extends Model
{
    use SoftDeletes;

    public const USED = "used";

    protected $fillable = ['name', 'is_percentage', 'valid_until', 'code', 'discount', 'quantity'];

    protected $casts = [
        'is_percentage' => 'boolean',
        'discount'      => 'integer',
        'quantity'      => 'integer',
        'used'          => 'integer',
        'is_locked'     => 'boolean',
    ];

    protected $attributes = [
        'used'      => 0,
        'is_locked' => FALSE,
    ];

    protected $dates = ['valid_until'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entrance()
    {
        return $this->belongsTo(Entrance::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
