<?php

namespace Modules\Ticket\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Modules\Ticket\Models\Event as RealEvent;

/**
 * Class Image
 *
 * @package Modules\Item\Models
 *
 * @property string                      id
 * @property string                      original
 * @property string                      cover
 * @property string                      thumbnail
 * @property string                      square
 * @property string                      facebook_cover
 * @property \Modules\Event\Models\Event event
 */
class Image extends Model
{
    protected $fillable = [
        'original',
        'cover',
        'thumbnail',
        'square',
        'facebook_cover',
    ];

    /**
     * @return string
     */
    public function getCoverUrlAttribute()
    {
        return (isset($this->attributes['cover']))
            ? \Storage::url($this->attributes['cover'])
            : NULL;
    }

    /**
     * @return string
     */
    public function getThumbnailUrlAttribute()
    {
        return (isset($this->attributes['thumbnail']))
            ? \Storage::url($this->attributes['thumbnail'])
            : NULL;
    }

    /**
     * @return string
     */
    public function getSquareUrlAttribute()
    {
        return (isset($this->attributes['square']))
            ? \Storage::url($this->attributes['square'])
            : NULL;
    }

    public function getFacebookCoverUrlAttribute()
    {
        return (isset($this->attributes['facebook_cover']))
            ? \Storage::url($this->attributes['facebook_cover'])
            : NULL;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(RealEvent::class);
    }
}
