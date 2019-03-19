<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 19/03/2019
 * Time: 14:40
 */

namespace Modules\Report\Services;

use Modules\Order\Models\Order;
use Modules\Report\Models\Report;

class ReportService
{
    protected $last_days = 6;

    /**
     * @param string $event
     *
     * @return \Modules\Report\Models\Report
     */
    public function valueSales(string $event)
    {
        $report = new Report();
        $orders = Order::where('event_id', $event)
            ->where('status', Order::PAID)
            ->get();

        $report->total = $orders->sum(function ($order) {
            return $order->amount + $order->fee - $order->discount;
        });

        $last_days = [];
        $today = today();
        $date = today()->subDays($this->last_days);
        do {
            $last_days[] = $orders->whereBetween('created_at', [$date->startOfDay(), $date->endOfDay()])->sum(function ($order) {
                return $order->amount + $order->fee - $order->discount;
            });
            $date->addDay();
        } while ($date->lte($today));

        $report->last_days = $last_days;

        return $report;
    }

    /**
     * @param string $event
     *
     * @return \Modules\Report\Models\Report
     */
    public function feeValues(string $event)
    {
        $report = new Report();
        $orders = Order::where('event_id', $event)
            ->where('status', Order::PAID)
            ->get();

        $report->total = $orders->sum('fee');

        $last_days = [];
        $today = today();
        $date = today()->subDays($this->last_days);
        do {
            $last_days[] = $orders->whereBetween('created_at', [$date->startOfDay(), $date->endOfDay()])->sum('fee');
            $date->addDay();
        } while ($date->lte($today));

        $report->last_days = $last_days;

        return $report;
    }

    /**
     * @param string $event
     *
     * @return \Modules\Report\Models\Report
     */
    public function canceledSales(string $event)
    {
        $report = new Report();
        $orders = Order::where('event_id', $event)
            ->whereIn('status', [Order::CANCELED, Order::REVERSED])
            ->get();

        $report->total = $orders->count();

        $last_days = [];
        $today = today();
        $date = today()->subDays($this->last_days);
        do {
            $last_days[] = $orders->whereBetween('created_at', [$date->startOfDay(), $date->endOfDay()])->count();
            $date->addDay();
        } while ($date->lte($today));

        $report->last_days = $last_days;

        return $report;
    }

    /**
     * @param string $event
     *
     * @return \Modules\Report\Models\Report
     */
    public function soldTickets(string $event)
    {
        $report = new Report();
        $orders = Order::where('event_id', $event)
            ->where('status', Order::PAID)
            ->get();

        $report->total = $orders->sum(function ($order) {
            return $order->tickets()->count();
        });

        $last_days = [];
        $today = today();
        $date = today()->subDays($this->last_days);
        do {
            $last_days[] = $orders->whereBetween('created_at', [$date->startOfDay(), $date->endOfDay()])->sum(function ($order) {
                return $order->tickets()->count();
            });
            $date->addDay();
        } while ($date->lte($today));

        $report->last_days = $last_days;

        return $report;
    }
}
