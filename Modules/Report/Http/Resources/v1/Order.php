<?php

namespace Modules\Report\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;

class Order extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'order'      => $this->id,
            'status'     => $this->status,
            'channel'    => $this->channel,
            'price'      => $this->amount - ($this->discount ?? 0),
            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'name'       => $this->channel === \Modules\Order\Models\Order::ONLINE_CHANNEL ? $this->customer->name : '',
            'email'      => $this->channel === \Modules\Order\Models\Order::ONLINE_CHANNEL ? $this->customer->email : '',

        ];
    }
}
