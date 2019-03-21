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
        $event = (new APIResourceManager())->setVersion(1, 'event');

        return [
            'id'            => $this->id,
            'types'         => 'orders',
            'attributes'    => [
                'status'         => $this->status,
                'amount'         => $this->amount,
                'discount'       => $this->when($this->channel === \Modules\Order\Models\Order::ONLINE_CHANNEL, $this->discount),
                'fee'            => $this->fee,
                'hash'           => $this->when($this->channel === \Modules\Order\Models\Order::ONLINE_CHANNEL, $this->hash),
                'ip'             => $this->when($this->channel === \Modules\Order\Models\Order::ONLINE_CHANNEL, $this->ip),
                'type'           => $this->when($this->channel === \Modules\Order\Models\Order::ONLINE_CHANNEL, $this->type),
                'channel'        => $this->channel,
                'transaction_id' => $this->when($this->channel === \Modules\Order\Models\Order::ONLINE_CHANNEL && $this->transaction_id !== NULL, $this->transaction_id),
                'costumer'       => $this->when($this->channel === \Modules\Order\Models\Order::ONLINE_CHANNEL, $order->resolve('Costumer')->make($this->costumer)),
                'tickets'        => $order->resolve('Ticket')->collection($this->tickets),
                'card'           => $this->when($this->channel === \Modules\Order\Models\Order::ONLINE_CHANNEL &&
                    ends_with($this->type, 'card'), $order->resolve('Card')->make($this->card)),
                'sale_point'     => $this->when($this->channel === \Modules\Order\Models\Order::PDV_CHANNEL, $order->resolve('SalePoint')->make($this->sale_point)),
            ],
            'relationships' => [
                'coupon' => $this->when($this->channel === \Modules\Order\Models\Order::ONLINE_CHANNEL && $this->coupon !== NULL,
                    $event->resolve('Coupon')->make($this->coupon)),
            ],
        ];
    }
}
