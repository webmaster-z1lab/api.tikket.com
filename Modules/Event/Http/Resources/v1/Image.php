<?php

namespace Modules\Event\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;

class Image extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'type'       => 'images',
            'attributes' => [
                'original'       => \Storage::url($this->original),
                'cover'          => $this->cover_url,
                'thumbnail'      => $this->thumbnail_url,
                'square'         => $this->square_url,
                'facebook_cover' => $this->facebook_cover_url,
            ],
        ];
    }
}
