<?php

namespace Modules\Cart\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;

class Card extends Resource
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
            'types'         => 'cards',
            'attributes'    => [
                'brand'        => $this->brand,
                'number'       => $this->number,
                'token'        => $this->token,
                'installments' => $this->installments,
                'parcel'       => $this->parcel,
            ],
            'relationships' => [
                'holder' => api_resource('Holder')->make($this->holder),
            ],
        ];
    }
}
