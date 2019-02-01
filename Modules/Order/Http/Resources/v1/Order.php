<?php

namespace Modules\Order\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;
use Juampi92\APIResources\APIResourceManager;

class Order extends Resource
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
            'id'         => $this->id,
            'types'      => 'orders',
            'attributes' => [
                'status'   => $this->status,
                'amount'   => $this->amount,
                'fee'      => $this->fee,
                'hash'     => $this->hash,
                'ip'       => $this->ip,
                'type'     => $this->type,
                'costumer' => $order->resolve('Costumer')->make($this->costumer),
                'tickets'  => $order->resolve('Ticket')->collection($this->tickets),
                'card'     => $this->when(ends_with($this->type, 'card'), $order->resolve('Card')->make($this->card)),
            ],
        ];
    }
}
