<?php

namespace Modules\Order\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;

class Ticket extends Resource
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
            'id'          => $this->id,
            'entrance_id' => $this->entrance_id,
            'entrance'    => $this->entrance,
            'lot'         => $this->lot,
            'name'        => $this->name,
            'document'    => substr($this->document, 0, 3) . '.***.***-**',
            'email'       => $this->email,
            'price'       => $this->price,
            'fee'         => $this->fee,
        ];
    }
}
