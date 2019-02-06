<?php

namespace Modules\Event\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Event\Http\Requests\ProducerRequest;
use Modules\Event\Repositories\ProducerRepository;

class ProducerController extends Controller
{
    /**
     * @var \Modules\Event\Repositories\ProducerRepository
     */
    private $repository;

    /**
     * ProducerController constructor.
     *
     * @param \Modules\Event\Repositories\ProducerRepository $repository
     */
    public function __construct(ProducerRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param \Modules\Event\Http\Requests\ProducerRequest $request
     * @param string                                       $event
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function store(ProducerRequest $request, string $event)
    {
        return api_resource('Event')->make($this->repository->insert($request->validated(), $event));
    }

    /**
     * @param string $event
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function destroy(string $event)
    {
        return api_resource('Event')->make($this->repository->destroy($event));
    }
}
