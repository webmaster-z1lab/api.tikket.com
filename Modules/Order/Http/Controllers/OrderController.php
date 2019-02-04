<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Order\Events\OrderCreated;
use Modules\Order\Http\Requests\OrderRequest;
use Modules\Order\Repositories\OrderRepository;

class OrderController extends Controller
{
    /**
     * @var \Modules\Order\Repositories\OrderRepository
     */
    private $repository;

    /**
     * OrderController constructor.
     *
     * @param \Modules\Order\Repositories\OrderRepository $repository
     */
    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param \Modules\Order\Http\Requests\OrderRequest $request
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     * @throws \Exception
     */
    public function store(OrderRequest $request)
    {
        $order = $this->repository->createByCart($request->get('cart'), $request->ip());

        event(new OrderCreated($order->id));

        return api_resource('Order')->make($order->fresh());
    }
}
