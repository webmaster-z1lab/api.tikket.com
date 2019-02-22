<?php

namespace Modules\Event\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;
use Juampi92\APIResources\APIResourceManager;

class Coupon extends Resource
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
            'type'          => 'coupons',
            'attributes'    => [
                'name'          => $this->name,
                'is_percentage' => $this->is_percentage,
                'valid_until'   => $this->valid_until->format('d/m/Y'),
                'code'          => $this->code,
                'discount'      => $this->discount,
                'quantity'      => $this->quantity,
            ],
            'relationships' => [
                'entrance' => $event->resolve('Entrance')->make($this->entrance),
            ],
        ];
    }
}
