<?php

namespace Modules\Event\Http\Resources\v1;

use Carbon\Carbon;
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
                'name'      => $this->name,
                'is_public' => $this->is_public,
                'is_free'   => $this->is_free,
                'min_buy'   => $this->min_buy,
                'max_buy'   => $this->max_buy,
                'starts_at' => $this->starts_at->format('d/m/Y'),
                'lot'       => $event->resolve('Lot')->make($this->getActiveLot()),
                'lots'      => $event->resolve('Lot')->collection($this->lots),
            ],
            'relationships' => [
                'event' => $event->resolve('Event')->make($this->event),
            ],
        ];
    }

    /**
     * @return mixed
     */
    private function getActiveLot()
    {
        $filter = $this->lots->filter(function ($value, $key) {
            $now = Carbon::now();

            return ($value->starts_at <= $now && $value->finishes_at >= $now);
        });

        return $filter->first();
    }
}
