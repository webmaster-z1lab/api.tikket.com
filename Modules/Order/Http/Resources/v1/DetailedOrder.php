<?php

namespace Modules\Order\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;
use Juampi92\APIResources\APIResourceManager;

/**
 * Class DetailedOrder
 *
 * @package Modules\Order\Http\Resources\v1
 *
 * @property \Modules\Order\Models\Order $resource
 */
class DetailedOrder extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     * @throws \Juampi92\APIResources\Exceptions\ResourceNotFoundException
     */
    public function toArray($request)
    {
        $order = (new APIResourceManager())->setVersion(1, 'order');
        $event = (new APIResourceManager())->setVersion(1, 'event');
        $ticket = (new APIResourceManager())->setVersion(1, 'ticket');

        return [
            'id'            => $this->resource->id,
            'types'         => 'orders',
            'attributes'    => [
                'status'         => $this->resource->status,
                'amount'         => $this->resource->amount,
                'discount'       => $this->resource->discount,
                'fee'            => $this->resource->fee,
                'hash'           => $this->when($this->resource->channel === \Modules\Order\Models\Order::ONLINE_CHANNEL, $this->resource->hash),
                'ip'             => $this->when($this->resource->channel === \Modules\Order\Models\Order::ONLINE_CHANNEL, $this->resource->ip),
                'type'           => $this->when($this->resource->channel === \Modules\Order\Models\Order::ONLINE_CHANNEL, $this->resource->type),
                'channel'        => $this->resource->channel,
                'transaction_id' => $this->when($this->resource->channel === \Modules\Order\Models\Order::ONLINE_CHANNEL && $this->resource->transaction_id !== NULL,
                    $this->resource->transaction_id),
                'created_at'     => $this->resource->created_at->format('d/m/Y H:i'),
                'costumer'       => $this->when($this->resource->channel === \Modules\Order\Models\Order::ONLINE_CHANNEL,
                    $order->resolve('Costumer')->make($this->resource->costumer)),
                'participants'   => $this->when(!in_array($this->resource->status, [\Modules\Order\Models\Order::PAID, \Modules\Order\Models\Order::REVERSED]),
                    $order->resolve('Ticket')->collection($this->resource->tickets)),
                'card'           => $this->when($this->channel === \Modules\Order\Models\Order::ONLINE_CHANNEL &&
                    ends_with($this->resource->type, 'card'), $order->resolve('Card')->make($this->resource->card)),
                'sale_point'     => $this->when($this->resource->channel === \Modules\Order\Models\Order::PDV_CHANNEL,
                    $order->resolve('SalePoint')->make($this->resource->sale_point)),
                'administrator'  => $this->when($this->resource->channel === \Modules\Order\Models\Order::ADMIN_CHANNEL,
                    $order->resolve('SalePoint')->make($this->resource->administrator)),
            ],
            'relationships' => [
                'coupon'  => $this->when($this->resource->channel === \Modules\Order\Models\Order::ONLINE_CHANNEL && $this->coupon !== NULL,
                    $event->resolve('Coupon')->make($this->resource->coupon)),
                'tickets' => $this->when(in_array($this->resource->status, [\Modules\Order\Models\Order::PAID, \Modules\Order\Models\Order::REVERSED]),
                    $ticket->resolve('Ticket')->collection($this->resource->actual_tickets)),
            ],
        ];
    }
}
