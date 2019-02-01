<?php

namespace Modules\Event\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Event\Repositories\EventRepository;
use Z1lab\JsonApi\Http\Controllers\ApiController;

class EventController extends ApiController
{
    /**
     * EventController constructor.
     *
     * @param \Modules\Event\Repositories\EventRepository $repository
     */
    public function __construct(EventRepository $repository)
    {
        parent::__construct($repository, 'Event');
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function store(Request $request)
    {
        return $this->makeResource($this->repository->create($request->all()));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string                   $id
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function update(Request $request, string $id)
    {
        return $this->makeResource($this->repository->update($request->all(), $id));
    }

    /**
     * @param string $url
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function findByUrl(string $url)
    {
        return $this->makeResource($this->repository->findByUrl($url));
    }
}
