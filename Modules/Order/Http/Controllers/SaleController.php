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
    }

    /**
     * @param \Modules\Order\Http\Requests\SaleRequest $request
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function store(SaleRequest $request)
    {
        return api_resource('Order')->make($this->repository->createBySale($request->validated()));
    }
}
