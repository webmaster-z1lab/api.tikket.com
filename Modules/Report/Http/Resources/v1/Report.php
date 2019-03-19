<?php

namespace Modules\Report\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;

class Report extends Resource
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
            'total'     => $this->resource['total'],
            'last_days' => $this->resource['last_days'],
        ];
    }
}
