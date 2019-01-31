<?php

namespace Modules\Cart\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;

class Holder extends Resource
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
            'name'       => $this->name,
            'document'   => $this->document,
            'birth_date' => $this->birth_date->format('Y-m-d'),
            'address'    => api_resource('Address')->make($this->address),
            'phone'      => api_resource('Phone')->make($this->phone),
        ];
    }
}
