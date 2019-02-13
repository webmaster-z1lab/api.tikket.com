<?php

namespace Modules\Event\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;

class Available extends Resource
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
            'lot_id'      => $this->lot_id,
            'number'      => $this->lot,
            'amount'      => $this->amount,
            'available'   => $this->available,
            'reserved'    => $this->reserved,
            'waiting'     => $this->waiting,
            'sold'        => $this->sold,
            'value'       => $this->value,
            'fee'         => $this->fee,
            'price'       => $this->price,
            'is_sold_out' => $this->is_sold_out,
            'is_active'   => $this->is_active,
            'starts_at'   => $this->starts_at->toW3cString(),
            'finishes_at' => $this->finishes_at->toW3cString(),
        ];
    }
}
