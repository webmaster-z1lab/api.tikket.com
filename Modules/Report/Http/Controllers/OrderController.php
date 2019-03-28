<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Report\Services\OrderService;

class OrderController extends Controller
{
    /**
     * @var \Modules\Report\Services\OrderService
     */
    private $service;

    /**
     * OrderReportController constructor.
     *
     * @param \Modules\Report\Services\OrderService $service
     */
    public function __construct(OrderService $service)
    {
        $this->service = $service;
        $this->middleware(['auth', 'can:admin,event']);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string                   $event
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function get(Request $request, string $event)
    {
        return $this->collectResource($this->service->all($event, $request->query('search')));
    }

    /**
     * @param $obj
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    private function collectResource($obj)
    {
        return api_resource('Order')->collection($obj);
    }
}
