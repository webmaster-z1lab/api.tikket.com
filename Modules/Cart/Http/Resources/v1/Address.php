<?php

namespace Modules\Cart\Http\Resources\v1;

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
            'id'         => $this->id,
            'type'       => 'addresses',
            'attributes' => [
                'street'      => $this->street,
                'number'      => $this->number,
                'complement'  => $this->complement,
                'district'    => $this->district,
                'postal_code' => $this->postal_code,
                'city'        => $this->city,
                'state'       => $this->state,
            ],
        ];
    }
}
