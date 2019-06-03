<?php

namespace Modules\Order\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;

class Boleto extends Resource
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
            'url'      => $this->url,
            'barcode'  => $this->barcode,
            'due_date' => optional($this->due_date)->format('d/m/Y'),
        ];
    }
}
