<?php

namespace Modules\Order\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;

class SalePoint extends Resource
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
            'user_id'  => $this->user_id,
            'name'     => $this->name,
            'document' => substr($this->document, 0, 3) . '.***.***-**',
            'email'    => $this->email,
        ];
    }
}
