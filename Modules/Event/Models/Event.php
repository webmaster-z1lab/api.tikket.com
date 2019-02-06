<?php

namespace Modules\Event\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

/**
 * Class Event
 *
 * @package Modules\Event\Models
 *
 * @property string                         name
 * @property string                         url
 * @property string                         description
 * @property string                         body
 * @property string                         cover
 * @property string                         category
 * @property string                         types
 * @property string                         referer
 * @property \Carbon\Carbon                 created_at
 * @property \Carbon\Carbon                 updated_at
 * @property \Carbon\Carbon                 deleted_at
 * @property \Carbon\Carbon                 starts_at
 * @property \Carbon\Carbon                 finishes_at
 * @property bool                           fee_is_hidden
 * @property bool                           is_active
 * @property bool                           is_public
 * @property \Modules\Event\Models\Entrance entrances
 * @property \Modules\Event\Models\Address  address
 * @property \Modules\Event\Models\Producer producer
 *
 */
class Event extends Model
{
    use SoftDeletes;

    const STATUS_ACTIVE = FALSE;
    const STATUS_PUBLIC = TRUE;
    const STATUS_FEE = TRUE;

    protected $attributes = [
        'is_active'     => self::STATUS_ACTIVE,
        'is_public'     => self::STATUS_PUBLIC,
        'fee_is_hidden' => self::STATUS_FEE,
    ];

    protected $fillable = [
        'name',
        'user_id',
        'url',
        'description',
        'body',
        'cover',
        'category',
        'types',
        'referer',
        'starts_at',
        'finishes_at',
        'fee_is_hidden',
        'is_public',
        'is_active',
    ];

    protected $dates = [
        'starts_at',
        'finishes_at',
    ];

    protected $casts = [
        'fee_is_hidden' => 'boolean',
        'is_public'     => 'boolean',
        'is_active'     => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entrances()
    {
        return $this->hasMany(Entrance::class);
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function address()
    {
        return $this->embedsOne(Address::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function producer()
    {
        return $this->hasOne(Producer::class);
    }
}
