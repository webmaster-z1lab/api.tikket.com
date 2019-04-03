<?php

namespace Modules\Cart\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Cart\Http\Requests\CardRequest;
use Modules\Cart\Http\Requests\CartRequest;
use Modules\Cart\Http\Requests\CouponRequest;
use Modules\Cart\Http\Requests\TicketRequest;
use Modules\Cart\Repositories\CartRepository;

class CartController extends Controller
{
    /**
     * @var \Modules\Cart\Repositories\CartRepository
     */
    private $repository;

    /**
     * CartController constructor.
     *
     * @param \Modules\Cart\Repositories\CartRepository $repository
     */
    public function __construct(CartRepository $repository)
    {
        $this->repository = $repository;
        $this->middleware('auth')->except(['store']);
        $this->middleware('can:cart_owner,cart')->except(['find', 'store']);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function find()
    {
        return api_resource('Cart')->make($this->repository->getByUser());
    }

    /**
     * @param \Modules\Cart\Http\Requests\CartRequest $request
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function store(CartRequest $request)
    {
        return api_resource('Cart')->make($this->repository->create($request->all()));
    }

    /**
     * @param \Modules\Cart\Http\Requests\TicketRequest $request
     * @param string                                    $id
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function tickets(TicketRequest $request, string $id)
    {
        return api_resource('Cart')->make($this->repository->setTickets($request->validated(), $id));
    }

    /**
     * @param \Modules\Cart\Http\Requests\CardRequest $request
     * @param string                                  $id
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function card(CardRequest $request, string $id)
    {
        return api_resource('Cart')->make($this->repository->setCard($request->validated(), $id));
    }

    /**
     * @param \Modules\Cart\Http\Requests\CouponRequest $request
     * @param string                                    $id
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function coupon(CouponRequest $request, string $id)
    {
        return api_resource('Cart')->make($this->repository->applyCoupon($request->get('coupon'), $id));
    }
}
