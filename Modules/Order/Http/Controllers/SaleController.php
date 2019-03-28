<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Order\Http\Requests\SaleRequest;
use Modules\Order\Repositories\OrderRepository;

class SaleController extends Controller
{
    /**
     * @var \Modules\Order\Repositories\OrderRepository
     */
    protected $repository;

    /**
     * SaleController constructor.
     *
     * @param \Modules\Order\Repositories\OrderRepository $repository
     */
    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
        $this->middleware('auth');
    }

    /**
     * @param \Modules\Order\Http\Requests\SaleRequest $request
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(SaleRequest $request)
    {
        $this->authorize('sell', $request->get('event'));

        return api_resource('Order')->make($this->repository->createBySale($request->validated()));
    }
}
