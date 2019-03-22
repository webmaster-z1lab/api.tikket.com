<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Report\Services\OrderReportService;

class OrderReportController extends Controller
{
    /**
     * @var \Modules\Report\Services\OrderReportService
     */
    private $service;

    /**
     * OrderReportController constructor.
     *
     * @param \Modules\Report\Services\OrderReportService $service
     */
    public function __construct(OrderReportService $service)
    {
        $this->service = $service;
    }

    /**
     * @param string $event
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function get(string $event)
    {
        return api_resource('Order')->collection($this->service->all($event));
    }
}
