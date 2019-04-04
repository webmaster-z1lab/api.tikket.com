<?php

namespace Modules\Ticket\Http\Resources\v1;

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
            'id'       => $this->id,
            'name'     => $this->name,
            'document' => $this->document,
            'email'    => $this->email,
        ];
    }
}
