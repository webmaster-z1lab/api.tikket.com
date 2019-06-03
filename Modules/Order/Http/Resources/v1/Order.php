<?php

namespace Modules\Order\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;
use Juampi92\APIResources\APIResourceManager;
use Modules\Order\Models\Order as Model;

/**
 * Class DetailedOrder
 *
 * @package Modules\Order\Http\Resources\v1
 *
 * @property \Modules\Order\Models\Order $resource
 */
class Order extends Resource
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
                'code'       => $this->resource->code,
                'status'     => $this->resource->status,
                'amount'     => $this->resource->amount,
                'discount'   => $this->resource->discount,
                'fee'        => $this->resource->fee,
                'channel'    => $this->resource->channel,
                'created_at' => $this->resource->created_at->format('d/m/Y H:i'),
                'updated_at' => $this->resource->updated_at->toW3cString(),
                'tickets'    => $order->resolve('Ticket')->collection($this->resource->tickets),
                'event'      => $order->resolve('Event')->make($this->resource->event),

                $this->mergeWhen($this->resource->channel === Model::ONLINE_CHANNEL, [
                    'hash'           => $this->resource->hash,
                    'ip'             => $this->resource->ip,
                    'type'           => $this->resource->type,
                    'customer'       => $order->resolve('Customer')->make($this->resource->customer),
                    'transaction_id' => $this->when($this->resource->transaction_id !== NULL, $this->resource->transaction_id),
                    'card'           => $this->when(ends_with($this->resource->type, 'card'),
                        $order->resolve('Card')->make($this->resource->card)),
                    'boleto'         => $this->when($this->resource->type === 'boleto', $order->resolve('Boleto')->make($this->resource->boleto)),
                ]),

                'sale_point' => $this->when($this->resource->channel === Model::PDV_CHANNEL,
                    $order->resolve('SalePoint')->make($this->resource->sale_point)),

                'administrator' => $this->when($this->resource->channel === Model::ADMIN_CHANNEL,
                    $order->resolve('SalePoint')->make($this->resource->administrator)),
            ],
            'relationships' => [
                'coupon' => $this->when($this->resource->channel === Model::ONLINE_CHANNEL && $this->resource->coupon !== NULL,
                    $event->resolve('Coupon')->make($this->resource->coupon)),

                'actual_tickets' => $this->when(in_array($this->resource->status, [Model::PAID, Model::REVERSED]),
                    $ticket->resolve('Ticket')->collection($this->resource->actual_tickets)),
            ],
        ];
    }
}
