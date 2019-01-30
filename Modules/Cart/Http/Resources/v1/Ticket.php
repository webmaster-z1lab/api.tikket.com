<?php

namespace Modules\Cart\Http\Resources\v1;

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
            'id'         => $this->id,
            'types'      => 'items',
            'attributes' => [
                'entrance_id' => $this->entrance_id,
                'lot'         => $this->lot,
                'name'        => $this->name,
                'document'    => substr($this->document, 0, 3) . '.***.***-**',
                'email'       => $this->email,
            ],
        ];
    }
}
