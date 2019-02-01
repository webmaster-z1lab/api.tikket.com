<?php

namespace Modules\Cart\Http\Resources\v1;

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
        $cart = (new APIResourceManager())->setVersion(1, 'cart');

        return [
            'id'           => $this->id,
            'brand'        => $this->brand,
            'number'       => str_pad($this->number, '16', '*', STR_PAD_LEFT),
            'token'        => $this->token,
            'installments' => $this->installments,
            'parcel'       => $this->parcel,
            'holder'       => $cart->resolve('Holder')->make($this->holder),
        ];
    }
}
