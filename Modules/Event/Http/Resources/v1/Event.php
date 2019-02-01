<?php

namespace Modules\Event\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;
use Juampi92\APIResources\APIResourceManager;

class Event extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     * @throws \Exception
     */
    public function toArray($request)
    {
        $event = (new APIResourceManager())->setVersion(1, 'event');

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
                'address'     => $event->resolve('Address')->make($this->address),
                'producer'    => $event->resolve('Producer')->make($this->producer),
            ],
            'relationships' => [
                'entrances' => $event->resolve('Entrance')->collection($this->entrances),
            ],
        ];
    }
}
