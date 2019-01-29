<?php

namespace Modules\Event\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;

class Lot extends Resource
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
            'type'       => 'lots',
            'attributes' => [
                'amount' => $this->amount,
                'value' => $this->value,
                'starts_at' => $this->starts_at->toW3cString(),
                'finishes_at' => $this->finishes_at->toW3cString(),
            ],
        ];
    }
}
