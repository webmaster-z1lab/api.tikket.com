<?php

namespace Modules\Cart\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;
use Juampi92\APIResources\APIResourceManager;

class Cart extends Resource
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
        $event = (new APIResourceManager())->setVersion(config('api.version.event'), 'event')->resolve('Event');

        return [
            'id'            => $this->id,
            'type'          => 'carts',
            'attributes'    => [
                'user_id'    => $this->user_id,
                'type'       => $this->type,
                'hash'       => $this->hash,
                'callback'   => $this->callback,
                'amount'     => $this->amount,
                'fee'        => $this->fee,
                'expires_at' => $this->expires_at->toW3cString(),
                'tickets'    => api_resource('Ticket')->collection($this->tickets),
                'card'       => $this->when(ends_with($this->type, 'card'), api_resource('Card')->make($this->card)),
            ],
            'relationships' => [
                'event' => $event->make($this->event),
            ],
        ];
    }
}
