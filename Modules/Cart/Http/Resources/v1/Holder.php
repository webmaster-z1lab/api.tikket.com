<?php

namespace Modules\Cart\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;
use Juampi92\APIResources\APIResourceManager;

class Holder extends Resource
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
        $cart = (new APIResourceManager())->setVersion(1, 'cart');

        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'document'   => $this->document,
            'birth_date' => $this->birth_date->format('Y-m-d'),
            'address'    => $cart->resolve('Address')->make($this->address),
            'phone'      => $cart->resolve('Phone')->make($this->phone),
        ];
    }
}
