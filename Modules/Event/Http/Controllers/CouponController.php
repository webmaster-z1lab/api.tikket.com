<?php

namespace Modules\Event\Http\Controllers;

use Modules\Event\Http\Requests\CouponRequest;
use Modules\Event\Repositories\CouponRepository;
use Z1lab\JsonApi\Http\Controllers\ApiController;

class CouponController extends ApiController
{
    /**
     * CouponController constructor.
     *
     * @param \Modules\Event\Repositories\CouponRepository $repository
     */
    public function __construct(CouponRepository $repository)
    {
        parent::__construct($repository, 'Coupon');
    }

    /**
     * @param \Modules\Event\Http\Requests\CouponRequest $request
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function store(CouponRequest $request)
    {
        return $this->makeResource($this->repository->create($request->validated()));
    }

    /**
     * @param \Modules\Event\Http\Requests\CouponRequest $request
     * @param string                                     $id
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function update(CouponRequest $request, string $id)
    {
        return $this->makeResource($this->repository->update($request->validated(), $id));
    }
}
