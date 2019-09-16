<?php

namespace Modules\Event\Models;

use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

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
}
