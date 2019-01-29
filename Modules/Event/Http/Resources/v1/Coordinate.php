<?php

namespace Modules\Event\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;

class Coordinate extends Resource
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
            'type'       => 'coordinates',
            'attributes' => [
                'type'     => $this->type,
                'location' => $this->location,
            ],
        ];
    }
}
