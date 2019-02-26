<?php

namespace Modules\Event\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes;

    const MASTER_PERMISSION = 'master';
    const ORGANIZER_PERMISSION = 'organizer';
    const CHECKIN_PERMISSION = 'check-in';
    const PDV_PERMISSION = 'pdv';

    const POSSIBLE_PERMISSIONS = [
        self::ORGANIZER_PERMISSION,
        self::CHECKIN_PERMISSION,
        self::PDV_PERMISSION,
    ];

    protected $fillable = ['type', 'email', 'parent_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
