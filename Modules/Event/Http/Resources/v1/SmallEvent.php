<?php

namespace Modules\Event\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;
use Juampi92\APIResources\APIResourceManager;

class SmallEvent extends Resource
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
                'name'           => $this->name,
                'url'            => $this->url,
                'category'       => $this->category,
                'types'          => $this->types,
                'starts_at'      => $this->starts_at->format('d/m/Y H:i'),
                'finishes_at'    => $this->finishes_at->format('d/m/Y H:i'),
                'is_public'      => $this->is_public,
                'is_active'      => $this->is_active,
                'is_locked'      => $this->is_locked,
                'status'         => $this->status,
                'created_at'     => $this->created_at,
                'updated_at'     => $this->updated_at,
                'address'        => $event->resolve('Address')->make($this->address),
            ]
        ];
    }
}
