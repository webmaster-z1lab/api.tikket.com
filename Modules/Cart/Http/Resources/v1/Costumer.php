<?php

namespace Modules\Cart\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;
use Juampi92\APIResources\APIResourceManager;

class Costumer extends Resource
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
            'document'   => substr($this->document, 0, 3) . '.***.***-**',
            'phone'      => $cart->resolve('Phone')->make($this->phone),
        ];
    }
}
