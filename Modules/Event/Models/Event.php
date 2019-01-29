<?php

namespace Modules\Event\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

/**
 * Class Event
 *
 * @package Modules\Event\Models
 *
 * @property string         name
 * @property string         url
 * @property string         description
 * @property string         body
 * @property string         cover
 * @property string         category
 * @property string         types
 * @property string         referer
 * @property \Carbon\Carbon created_at
 * @property \Carbon\Carbon updated_at
 * @property \Carbon\Carbon deleted_at
 * @property \Carbon\Carbon starts_at
 * @property \Carbon\Carbon finishes_at
 * @property bool           is_active
 * @property bool           is_public
 *
 */
class Event extends Model
{
    use SoftDeletes;

    const STATUS_ACTIVE = TRUE;
    const STATUS_PUBLIC = FALSE;

    protected $attributes = [
        'is_active' => self::STATUS_ACTIVE,
        'is_public' => self::STATUS_PUBLIC,
    ];

    protected $fillable = [
        'name',
        'url',
        'description',
        'body',
        'cover',
        'category',
        'types',
        'referer',
        'starts_at',
        'finishes_at',
        'is_public',
        'is_active',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'starts_at',
        'finishes_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entrances()
    {
        return $this->hasMany(Entrance::class);
    }
}
