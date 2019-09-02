<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 02/09/2019
 * Time: 17:19
 */

namespace Modules\Event\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class SimpleEvent
 *
 * @package Modules\Event\Http\Resources\v1
 *
 * @property-read  \Modules\Event\Models\Event $resource
 */
class SimpleEvent extends Resource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->resource->id,
            'type'       => 'events',
            'attributes' => [
                'name'           => $this->resource->name,
                'cover'          => $this->resource->image->cover_url,
                'facebook_cover' => $this->resource->image->facebook_cover_url,
                'url'            => $this->resource->url,
                'day'            => $this->resource->starts_at->format('d'),
                'month'          => $this->resource->starts_at->formatLocalized('%b'),
                'hour'           => $this->resource->starts_at->format('H:i'),
                'address'        => $this->resource->address->city.' - '.$this->resource->address->state,
                'producer'       => $this->resource->producer->name,
            ],
        ];
    }
}
