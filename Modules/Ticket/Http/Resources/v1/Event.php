<?php

namespace Modules\Ticket\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;
use Juampi92\APIResources\APIResourceManager;

class Event extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     * @throws \Exception
     */
    public function toArray($request)
    {
        $ticket = (new APIResourceManager())->setVersion(1, 'ticket');

        return [
            'id'        => $this->id,
            'event_id'  => $this->event_id,
            'name'      => $this->name,
            'url'       => $this->url,
            'address'   => $this->address,
            'starts_at' => $this->starts_at->format('d/m/Y H:i'),
            'image'     => $ticket->resolve('Image')->make($this->image),
        ];
    }
}
