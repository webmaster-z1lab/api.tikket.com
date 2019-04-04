<?php

namespace Modules\Ticket\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;
use Juampi92\APIResources\APIResourceManager;

class Ticket extends Resource
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
            'id'         => $this->id,
            'type'       => 'tickets',
            'attributes' => [
                'name'        => $this->name,
                'lot'         => $this->lot,
                'code'        => $this->code,
                'status'      => $this->status,
                'first_owner' => $this->first_owner,
                'participant' => $ticket->resolve('Participant')->make($this->participant),
                'event'       => $ticket->resolve('Event')->make($this->event),
            ],
        ];
    }
}
