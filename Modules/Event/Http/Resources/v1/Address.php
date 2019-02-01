<?php

namespace Modules\Event\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;
use Juampi92\APIResources\APIResourceManager;

class Address extends Resource
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
            'id'          => $this->id,
            'name'        => $this->name,
            'street'      => $this->street,
            'number'      => $this->number,
            'district'    => $this->district,
            'complement'  => $this->complement,
            'city'        => $this->city,
            'state'       => $this->state,
            'postal_code' => $this->postal_code,
            'formatted'   => $this->formatted,
            'maps_url'    => $this->maps_url,
            'coordinate'  => $event->resolve('Coordinate')->make($this->coordinate),
        ];
    }
}
