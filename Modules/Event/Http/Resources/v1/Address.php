<?php

namespace Modules\Event\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;

class Address extends Resource
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
            'type'          => 'producers',
            'attributes'    => [
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
            ],
            'relationships' => [
                'coordinate' => api_resource('Coordinate')->make($this->coordinate),
            ],
        ];
    }
}
