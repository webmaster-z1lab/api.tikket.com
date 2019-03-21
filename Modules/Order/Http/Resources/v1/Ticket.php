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
            'name'        => $this->when($this->name !== NULL, $this->name),
            'document'    => $this->when($this->document !== NULL, substr($this->document, 0, 3) . '.***.***-**'),
            'email'       => $this->when($this->email !== NULL, $this->email),
            'value'       => $this->value,
            'price'       => $this->price,
            'fee'         => $this->fee,
            'code' => $this->when($this->code !== NULL, $this->code)
        ];
    }
}
