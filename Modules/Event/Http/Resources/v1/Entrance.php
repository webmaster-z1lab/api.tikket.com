<?php

namespace Modules\Event\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;

class Entrance extends Resource
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
            'id'            => $this->id,
            'type'          => 'producers',
            'attributes'    => [
                'name'      => $this->name,
                'is_public' => $this->is_public,
                'is_free'   => $this->is_free,
                'min_buy'   => $this->min_buy,
                'max_buy'   => $this->max_buy,
            ],
            'relationships' => [
                'lots' => api_resource('Lot')->collection($this->lots),
            ],
        ];
    }
}
