<?php

namespace Modules\Event\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Event\Http\Requests\EntrancesRequest;
use Modules\Event\Repositories\EntranceRepository;

class EntranceController extends Controller
{
    /**
     * @var \Modules\Event\Repositories\EntranceRepository
     */
    private $repository;

    /**
     * EntranceController constructor.
     *
     * @param \Modules\Event\Repositories\EntranceRepository $repository
     */
    public function __construct(EntranceRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $event
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function index(string $event)
    {
        return api_resource('Entrance')->collection($this->repository->get($event));
    }

    /**
     * @param \Modules\Event\Http\Requests\EntrancesRequest $request
     * @param string                                        $event
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function store(EntrancesRequest $request, string $event)
    {
        return api_resource('Event')->make($this->repository->insert($request->validated(), $event));
    }

    /**
     * @param \Modules\Event\Http\Requests\EntrancesRequest $request
     * @param string                                        $event
     * @param string                                        $id
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function update(EntrancesRequest $request, string $event, string $id)
    {
        return api_resource('Event')->make($this->repository->update($request->validated(), $event, $id));
    }

    /**
     * @param string $event
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     * @throws \Exception
     */
    public function destroy(string $event, string  $id)
    {
        return api_resource('Event')->make($this->repository->destroy($event, $id));
    }

    /**
     * @param string $event
     * @param string $id
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function show(string $event, string $id)
    {
        return api_resource('Entrance')->make($this->repository->find($event, $id));
    }
}
