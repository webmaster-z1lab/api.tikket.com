<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Report\Services\ReportService;

class ReportController extends Controller
{
    /**
     * @var \Modules\Report\Services\ReportService
     */
    private $service;

    /**
     * ReportController constructor.
     *
     * @param \Modules\Report\Services\ReportService $service
     */
    public function __construct(ReportService $service)
    {
        $this->service = $service;
    }

    /**
     * @param string $event
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function valueSales(string $event)
    {
        return api_resource('Report')->make($this->service->valueSales($event));
    }

    /**
     * @param string $event
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function feeValues(string $event)
    {
        return api_resource('Report')->make($this->service->feeValues($event));
    }

    /**
     * @param string $event
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function canceledSales(string $event)
    {
        return api_resource('Report')->make($this->service->canceledSales($event));
    }

    /**
     * @param string $event
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function soldTickets(string $event)
    {
        return api_resource('Report')->make($this->service->soldTickets($event));
    }
}
