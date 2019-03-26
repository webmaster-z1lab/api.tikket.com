<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Order\Models\Order;
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
        return $this->makeResource($this->service->valueSales($event, Order::PAID));
    }

    /**
     * @param string $event
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function canceledValues(string $event)
    {
        return $this->makeResource($this->service->valueSales($event, Order::REVERSED));
    }

    /**
     * @param string $event
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function pendingValues(string $event)
    {
        return $this->makeResource($this->service->valueSales($event, Order::WAITING));
    }


    /**
     * @param string $event
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function amountValues(string $event)
    {
        return $this->makeResource($this->service->amountValues($event));
    }

    /**
     * @param string $event
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function canceledSales(string $event)
    {
        return $this->makeResource($this->service->numberOfSales($event, Order::REVERSED));
    }

    /**
     * @param string $event
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function soldTickets(string $event)
    {
        return $this->makeResource($this->service->reportTickets($event, Order::PAID));
    }

    /**
     * @param string $event
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function pendingTickets(string $event)
    {
        return $this->makeResource($this->service->reportTickets($event, Order::WAITING));
    }

    /**
     * @param string $event
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function canceledTickets(string $event)
    {
        return $this->makeResource($this->service->reportTickets($event, Order::REVERSED));
    }

    /**
     * @param string $event
     * @param string $pdv
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function salePointTickets(string $event, string $pdv)
    {
        return $this->makeResource($this->service->salePointReports($event, $pdv, function ($order) {
            return $order->tickets()->count();
        }));
    }

    /**
     * @param string $event
     * @param string $pdv
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function salePointValues(string $event, string $pdv)
    {
        return $this->makeResource($this->service->salePointReports($event, $pdv, function ($order) {
            return $order->amount + $order->fee;
        }));
    }

    /**
     * @param $obj
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    private function makeResource($obj)
    {
        return api_resource('Report')->make($obj);
    }
}
