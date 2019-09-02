<?php

namespace Modules\Event\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Image
 *
 * @package Modules\Item\Models
 *
 * @property string                      id
 * @property string                      event_id
 * @property string                      original
 * @property string                      cover
 * @property-read  string                cover_url
 * @property string                      thumbnail
 * @property-read  string                thumbnail_url
 * @property string                      square
 * @property-read  string                square_url
 * @property string                      facebook_cover
 * @property-read string                 facebook_cover_url
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
            : 'https://via.placeholder.com/1280x720';
    }

    /**
     * @return string
     */
    public function getThumbnailUrlAttribute()
    {
        return (isset($this->attributes['thumbnail']))
            ? \Storage::url($this->attributes['thumbnail'])
            : 'https://via.placeholder.com/250';
    }

    /**
     * @return string
     */
    public function getSquareUrlAttribute()
    {
        return (isset($this->attributes['square']))
            ? \Storage::url($this->attributes['square'])
            : 'https://via.placeholder.com/640';
    }

    /**
     * @return string
     */
    public function getFacebookCoverUrlAttribute()
    {
        return (isset($this->attributes['facebook_cover']))
            ? \Storage::url($this->attributes['facebook_cover'])
            : 'https://via.placeholder.com/1200x630';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
