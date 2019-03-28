<?php

namespace Modules\Event\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Modules\Event\Http\Requests\PermissionRequest;
use Modules\Event\Models\Permission;
use Modules\Event\Repositories\PermissionRepository;

class PermissionController extends Controller
{
    /**
     * Set the base repository
     *
     * @var \Modules\Event\Repositories\PermissionRepository
     */
    protected $repository;

    /**
     * Set the resource base ClassName
     *
     * @var string
     */
    protected $resource;

    /**
     * PermissionController constructor.
     *
     * @param \Modules\Event\Repositories\PermissionRepository $repository
     */
    public function __construct(PermissionRepository $repository)
    {
        $this->repository = $repository;
        $this->resource = 'Permission';
    }

    /**
     * @param string $event
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function index(string $event)
    {
        return $this->collectResource($this->repository->getByEvent($event));
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
     * @param string $event
     * @param string $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $event, string $id)
    {
        $this->repository->destroy($id);

        return response()->json(NULL, Response::HTTP_NO_CONTENT);
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
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function getByUserAndEvent(string $event)
    {
        return $this->makeResource($this->repository->getByUserAndEvent($event));
    }

    /**
     * @param string $event
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function salePoints(string $event)
    {
        return $this->collectResource($this->repository->getByEventAndType($event, Permission::PDV));
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

    /**
     * @param $obj
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    protected function makeResource($obj)
    {
        return api_resource($this->resource)->make($obj);
    }

    /**
     * @param $collection
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    protected function collectResource($collection)
    {
        return api_resource($this->resource)->collection($collection);
    }
}
