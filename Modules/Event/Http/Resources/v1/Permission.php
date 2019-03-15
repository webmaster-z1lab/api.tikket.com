<?php

namespace Modules\Event\Http\Resources\v1;

use Illuminate\Http\Resources\Json\Resource;
use Juampi92\APIResources\APIResourceManager;

class Permission extends Resource
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
            'type'          => 'permissions',
            'attributes'    => [
                'type'        => $this->type,
                'name'        => $this->name,
                'description' => $this->description,
                'email'       => $this->email,
                'parent_id'   => $this->parent_id,
            ],
            'relationships' => [
                'event' => $event->resolve('SmallEvent')->make($this->event),
            ],
        ];
    }
}
