<?php

namespace Modules\Event\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

/**
 * Class Event
 *
 * @package Modules\Event\Models
 *
 * @property string                                   name
 * @property string                                   url
 * @property string                                   description
 * @property string                                   body
 * @property string                                   category
 * @property string                                   types
 * @property string                                   referer
 * @property \Carbon\Carbon                           starts_at
 * @property \Carbon\Carbon                           finishes_at
 * @property integer                                  fee_percentage
 * @property bool                                     fee_is_hidden
 * @property bool                                     is_active
 * @property bool                                     is_public
 * @property bool                                     is_locked
 * @property \Illuminate\Database\Eloquent\Collection entrances
 * @property \Modules\Event\Models\Address            address
 * @property \Modules\Event\Models\Producer           producer
 * @property \Modules\Event\Models\Image              image
 * @property-read \Carbon\Carbon                      created_at
 * @property-read \Carbon\Carbon                      updated_at
 */
class Event extends Model
{
    use SoftDeletes;

    public const DRAFT = 'draft';
    public const COMPLETE = 'completed';
    public const FINALIZED = 'finalized';
    public const CANCELED = 'canceled';
    public const PUBLISHED = 'published';

    const STATUS_ACTIVE = FALSE;
    const STATUS_PUBLIC = TRUE;
    const STATUS_FEE = TRUE;
    const STATUS_LOCKED = FALSE;
    const FEE_PERCENTAGE = 10;

    protected $attributes = [
        'is_active'      => self::STATUS_ACTIVE,
        'is_public'      => self::STATUS_PUBLIC,
        'fee_is_hidden'  => self::STATUS_FEE,
        'fee_percentage' => self::FEE_PERCENTAGE,
        'is_locked'      => self::STATUS_LOCKED,
        'status'         => self::DRAFT,
    ];

    protected $fillable = [
        'name',
        'user_id',
        'url',
        'description',
        'body',
        'category',
        'types',
        'referer',
        'starts_at',
        'finishes_at',
        'fee_percentage',
        'fee_is_hidden',
        'is_public',
        'is_active',
        'is_locked',
        'status',
    ];

    protected $dates = [
        'starts_at',
        'finishes_at',
    ];

    protected $casts = [
        'fee_percentage' => 'integer',
        'fee_is_hidden'  => 'boolean',
        'is_public'      => 'boolean',
        'is_active'      => 'boolean',
        'is_locked'      => 'boolean',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function producer()
    {
        return $this->belongsTo(Producer::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function image()
    {
        return $this->hasOne(Image::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
