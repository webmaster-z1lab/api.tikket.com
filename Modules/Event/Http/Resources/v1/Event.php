<?php

namespace Modules\Event\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;

class Event extends Resource
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
            'id'            => $this->id,
            'type'          => 'events',
            'attributes'    => [
                'name'        => $this->name,
                'url'         => $this->url,
                'description' => $this->description,
                'body'        => $this->body,
                'cover'       => $this->cover,
                'category'    => $this->category,
                'types'       => $this->types,
                'referer'     => $this->referer,
                'starts_at'   => $this->starts_at->toW3cString(),
                'finishes_at' => $this->finishes_at->toW3cString(),
                'is_public'   => $this->is_public,
                'is_active'   => $this->is_active,
                'created_at'  => $this->created_at->toW3cString(),
                'updated_at'  => $this->updated_at->toW3cString(),
                'address'     => api_resource('Address')->make($this->address),
                'producer'    => api_resource('Producer')->make($this->producer),
            ],
            'relationships' => [
                'entrances' => api_resource('Entrance')->collection($this->entrances),
            ],
        ];
    }
}
