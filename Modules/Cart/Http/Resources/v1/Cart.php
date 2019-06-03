<?php

namespace Modules\Cart\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;
use Juampi92\APIResources\APIResourceManager;

class Cart extends Resource
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
        $event = (new APIResourceManager())->setVersion(1, 'event')->resolve('Event');

        return [
            'id'            => $this->id,
            'type'          => 'carts',
            'attributes'    => [
                'user_id'        => $this->user_id,
                'type'           => $this->type,
                'hash'           => $this->hash,
                'callback'       => $this->callback,
                'amount'         => $this->amount,
                'fee'            => $this->fee,
                'fee_percentage' => $this->fee_percentage,
                'fee_is_hidden'  => $this->fee_is_hidden,
                'discount'       => $this->discount,
                'is_free'        => $this->is_free,
                'expires_at'     => $this->expires_at->toW3cString(),
                'tickets'        => $cart->resolve('Ticket')->collection($this->tickets),
                'card'           => $this->when(ends_with($this->type, 'card'), $cart->resolve('Card')->make($this->card)),
                'customer'       => $this->when($this->customer !== NULL, $cart->resolve('Customer')->make($this->customer)),
            ],
            'relationships' => [
                'event' => $event->make($this->event),
            ],
        ];
    }
}
