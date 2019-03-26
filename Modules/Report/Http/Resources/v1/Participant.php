<?php

namespace Modules\Report\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;

class Participant extends Resource
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
            'ticket'     => $this->id,
            'code'       => $this->code,
            'order'      => $this->order_id,
            'status'     => $this->order->status,
            'name'       => $this->participant->name,
            'email'      => $this->participant->email,
            'entrance'   => $this->name,
            'channel'    => $this->order->channel,
            'created_at' => $this->order->created_at->format('d/m/Y H:i'),
        ];
    }
}
