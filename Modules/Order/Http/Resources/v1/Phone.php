<?php

namespace Modules\Order\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class Phone
 *
 * @package Modules\Order\Http\Resources\v1
 *
 * @property \Modules\Order\Models\Phone $resource
 */
class Phone extends Resource
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
            'id'        => $this->resource->id,
            'area_code' => $this->resource->area_code,
            'phone'     => $this->resource->phone,
            'formatted' => $this->resource->formatted_phone,
        ];
    }
}
