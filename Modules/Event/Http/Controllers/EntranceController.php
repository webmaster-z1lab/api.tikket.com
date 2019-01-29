<?php

namespace Modules\Event\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Event\Repositories\EntranceRepository;
use Z1lab\JsonApi\Http\Controllers\ApiController;

class EntranceController extends ApiController
{
    /**
     * EntranceController constructor.
     *
     * @param \Modules\Event\Repositories\EntranceRepository $repository
     */
    public function __construct(EntranceRepository $repository)
    {
        parent::__construct($repository, 'Entrance');
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function store(Request $request, string $event)
    {
        return $this->makeResource($this->repository->create($request->all()));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string                   $id
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function update(Request $request, string $event, string $id)
    {
        return $this->makeResource($this->repository->update($request->all(), $id));
    }
}
