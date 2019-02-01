<?php

namespace Modules\Cart\Http\Resources\v1;

use Carbon\Carbon;
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
                'user_id'    => $this->user_id,
                'type'       => $this->type,
                'hash'       => $this->hash,
                'callback'   => $this->callback,
                'amount'     => $this->amount,
                'fee'        => $this->fee,
                'expires_at' => Carbon::now()->diffInSeconds($this->expires_at),
                'tickets'    => $cart->resolve('Ticket')->collection($this->tickets),
                'card'       => $this->when(ends_with($this->type, 'card'), $cart->resolve('Card')->make($this->card)),
            ],
            'relationships' => [
                'event' => $event->make($this->event),
            ],
        ];
    }
}
