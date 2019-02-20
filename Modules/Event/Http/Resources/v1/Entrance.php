<?php

namespace Modules\Event\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;
use Juampi92\APIResources\APIResourceManager;

class Entrance extends Resource
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
        $event = (new APIResourceManager())->setVersion(1, 'event');

        return [
            'id'            => $this->id,
            'type'          => 'entrances',
            'attributes'    => [
                'name'        => $this->name,
                'is_public'   => $this->is_public,
                'is_free'     => $this->is_free,
                'min_buy'     => $this->min_buy,
                'max_buy'     => $this->max_buy,
                'description' => $this->description,
                'starts_at'   => $this->starts_at->format('d/m/Y'),
                'lot'         => $event->resolve('Available')->make($this->available),
                'lots'        => $event->resolve('Lot')->collection($this->lots),
            ],
            'relationships' => [
                'event' => $event->resolve('Event')->make($this->event),
            ],
        ];
    }
}
