<?php

namespace Modules\Event\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Entrance
 *
 * @package Modules\Event\Models
 *
 * @property string                    name
 * @property bool                      is_public
 * @property int                       min_buy
 * @property int                       max_buy
 * @property \Carbon\Carbon            starts_at
 * @property \Modules\Event\Models\Lot lots
 */
class Entrance extends Model
{
    const STATUS_PUBLIC = TRUE;
    const STATUS_FREE = FALSE;
    const MIN_BUY = 1;
    const MAX_BUY = 5;

    protected $attributes = [
        'is_public' => self::STATUS_PUBLIC,
        'is_free'   => self::STATUS_PUBLIC,
        'min_buy'   => self::MIN_BUY,
        'max_buy'   => self::MAX_BUY,
    ];

    protected $fillable = [
        'name',
        'is_free',
        'min_buy',
        'max_buy',
        'starts_at',
    ];

    protected $dates = [
        'starts_at',
    ];

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsMany
     */
    public function lots()
    {
        return $this->embedsMany(Lot::class);
    }
}
