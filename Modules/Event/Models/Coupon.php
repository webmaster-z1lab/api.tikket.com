<?php

namespace Modules\Event\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'is_percentage', 'valid_until', 'code', 'discount', 'quantity'];

    protected $casts = [
        'is_percentage' => 'boolean',
        'discount'      => 'integer',
        'quantity'      => 'integer',
    ];

    protected $dates = ['valid_until'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entrance()
    {
        return $this->belongsTo(Entrance::class);
    }
}
