<?php

namespace Modules\Event\Models;

use App\Models\DatabaseNotification;
use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

/**
 * Class Permission
 *
 * @package Modules\Event\Models
 *
 * @property string                      $type
 * @property string                      $email
 * @property string                      $name
 * @property string                      $description
 * @property string                      $parent_id
 * @property \Modules\Event\Models\Event $event
 */
class Permission extends Model
{
    use SoftDeletes, Notifiable;

    public const MASTER    = 'master';
    public const ORGANIZER = 'organizer';
    public const CHECKIN   = 'checkin';
    public const PDV       = 'pdv';

    protected $fillable = [
        'type',
        'email',
        'parent_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @return string
     */
    public function getNameAttribute(): string
    {
        return config('event.levels.'.$this->attributes['type'].'.name');
    }

    /**
     * @return string
     */
    public function getDescriptionAttribute(): string
    {
        return config('event.levels.'.$this->attributes['type'].'.description');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notifications(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')->orderBy('created_at', 'desc')->limit(100);
    }

    /**
     * The channels the user receives notification broadcasts on.
     *
     * @return string
     */
    public function receivesBroadcastNotificationsOn(): string
    {
        return 'users.'.$this->parent_id;
    }
}
