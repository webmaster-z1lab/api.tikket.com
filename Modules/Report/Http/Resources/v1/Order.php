<?php

namespace Modules\Report\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;

class Order extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'types'      => 'orders',
            'attributes' => [
                'status'         => $this->status,
                'amount'         => $this->amount,
                'discount'       => $this->discount,
                'fee'            => $this->fee,
                'type'           => $this->type,
                'tickets'        => $this->tickets->count(),
            ]
        ];
    }
}
