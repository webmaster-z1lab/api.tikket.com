<?php

namespace Modules\Order\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;
use Juampi92\APIResources\APIResourceManager;

class Card extends Resource
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
            'id'           => $this->id,
            'brand'        => $this->brand,
            'number'       => $this->number,
            'token'        => $this->token,
            'installments' => $this->installments,
            'parcel'       => $this->parcel,
            'holder'       => $order->resolve('Holder')->make($this->holder),
        ];
    }
}
