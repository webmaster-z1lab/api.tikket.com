<?php

namespace Modules\Event\Http\Controllers;

use Modules\Event\Http\Requests\PermissionRequest;
use Modules\Event\Repositories\PermissionRepository;
use Z1lab\JsonApi\Http\Controllers\ApiController;

class PermissionController extends ApiController
{
    /**
     * PermissionController constructor.
     *
     * @param \Modules\Event\Repositories\PermissionRepository $repository
     */
    public function __construct(PermissionRepository $repository)
    {
        parent::__construct($repository, 'Permission');
    }

    /**
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function index()
    {
        return $this->collectResource($this->repository->getByEvent(\Route::current()->parameter('event', '')));
    }

    /**
     * @param \Modules\Event\Http\Requests\PermissionRequest $request
     * @param string                                         $event
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function store(PermissionRequest $request, string $event)
    {
        $data = $request->validated();
        $data['event_id'] = $event;

        return $this->makeResource($this->repository->create($data));
    }

    /**
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function getByUser()
    {
        return $this->collectResource($this->repository->getByUser());
    }

    /**
     * @param string $event
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLevels(string $event)
    {
        $possible = $this->repository->getPossibleLevels($event);

        if (empty($possible)) return response()->json($possible, 204);

        return response()->json($possible);
    }
}
