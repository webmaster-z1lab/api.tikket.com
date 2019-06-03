<?php

namespace Modules\Order\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;
use Juampi92\APIResources\APIResourceManager;

class Customer extends Resource
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
        $order = (new APIResourceManager())->setVersion(1, 'order');

        return [
            'id'       => $this->id,
            'user_id'  => $this->user_id,
            'name'     => $this->name,
            'email'    => $this->email,
            'document' => substr($this->document, 0, 3).'.***.***-**',
            'phone'    => $order->resolve('Phone')->make($this->phone),
            'address'  => $this->when(filled($this->address), $order->resolve('Address')->make($this->address)),
        ];
    }
}
