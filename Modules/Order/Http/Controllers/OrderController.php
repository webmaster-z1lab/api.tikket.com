<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Modules\Order\Http\Requests\OrderRequest;
use Modules\Order\Http\Requests\StatusRequest;
use Modules\Order\Repositories\OrderRepository;
use Modules\Order\Services\OrderService;

class OrderController extends Controller
{
    /**
     * @var \Modules\Order\Repositories\OrderRepository
     */
    private $repository;

    private $resource;
    /**
     * @var \Modules\Order\Services\OrderService
     */
    private $service;

    /**
     * OrderController constructor.
     *
     * @param \Modules\Order\Repositories\OrderRepository $repository
     * @param \Modules\Order\Services\OrderService        $service
     */
    public function __construct(OrderRepository $repository, OrderService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
        $this->resource = 'Order';
    }

    /**
     * @param \Modules\Order\Http\Requests\OrderRequest $request
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     * @throws \Exception
     */
    public function store(OrderRequest $request)
    {
        return $this->makeResource($this->repository->createByCart($request->get('cart'), $request->ip()));
    }

    /**
     * @param string $id
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function show(string $id)
    {
        return $this->makeResource($this->repository->find($id));
    }

    /**
     * @param string $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $this->service->cancel($id);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @param \Modules\Order\Http\Requests\StatusRequest $request
     * @param string                                     $id
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function status(StatusRequest $request, string $id)
    {
        return $this->makeResource($this->repository->setStatus($request->validated(), $id));
    }

    /**
     * @param $obj
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    private function makeResource($obj)
    {
        return api_resource($this->resource)->make($obj);
    }
}
