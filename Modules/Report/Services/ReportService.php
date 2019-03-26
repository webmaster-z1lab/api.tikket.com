<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 19/03/2019
 * Time: 14:40
 */

namespace Modules\Report\Services;

use Modules\Event\Models\Permission;
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
            $last_days[] = $orders->filter( function ($order, $key) use ($date) {
                return $order->created_at->isSameDay($date);
            })->sum(function ($order) {
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
    public function amountValues(string $event)
    {
        $report = new Report();
        $orders = Order::where('event_id', $event)
            ->where('status', Order::PAID)
            ->get();

        $report->total = $orders->sum('amount');

        $last_days = [];
        $today = today();
        $date = today()->subDays($this->last_days);
        do {
            $last_days[] = $orders->filter( function ($order, $key) use ($date) {
                return $order->created_at->isSameDay($date);
            })->sum('amount');
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
            $last_days[] = $orders->filter( function ($order, $key) use ($date) {
                return $order->created_at->isSameDay($date);
            })->count();
            $date->addDay();
        } while ($date->lte($today));

        $report->last_days = $last_days;

        return $report;
    }

    /**
     * @param string $event
     * @param string $status
     *
     * @return \Modules\Report\Models\Report
     */
    public function reportTickets(string $event, string $status)
    {
        $report = new Report();
        $orders = Order::where('event_id', $event)
            ->where('status', $status)
            ->get();

        $report->total = $orders->sum(function ($order) {
            return $order->tickets()->count();
        });

        $last_days = [];
        $today = today();
        $date = today()->subDays($this->last_days);
        do {
            $last_days[] = $orders->filter( function ($order, $key) use ($date) {
                return $order->created_at->isSameDay($date);
            })->sum(function ($order) {
                return $order->tickets()->count();
            });
            $date->addDay();
        } while ($date->lte($today));

        $report->last_days = $last_days;

        return $report;
    }

    /**
     * @param string $event
     * @param string $pdv
     *
     * @return \Modules\Report\Models\Report
     */
    public function salePointTickets(string $event, string $pdv)
    {
        $permission = Permission::whereKey($pdv)->where('event_id', $event)->first();

        if ($permission === NULL) abort(404);

        $report = new Report();

        $orders = Order::where('event_id', $event)
            ->where('channel', Order::PDV_CHANNEL)
            ->where('sale_point.email', $permission->email)
            ->get();

        $report->total = $orders->sum(function ($order) {
            return $order->tickets()->count();
        });

        $last_days = [];
        $today = today();
        $date = today()->subDays($this->last_days);
        do {
            $last_days[] = $orders->filter( function ($order, $key) use ($date) {
                return $order->created_at->isSameDay($date);
            })->sum(function ($order) {
                return $order->tickets()->count();
            });
            $date->addDay();
        } while ($date->lte($today));

        $report->last_days = $last_days;

        return $report;
    }

    /**
     * @param string $event
     * @param string $pdv
     *
     * @return \Modules\Report\Models\Report
     */
    public function salePointValues(string $event, string $pdv)
    {
        $permission = Permission::whereKey($pdv)->where('event_id', $event)->first();

        if ($permission === NULL) abort(404);

        $report = new Report();

        $orders = Order::where('event_id', $event)
            ->where('channel', Order::PDV_CHANNEL)
            ->where('sale_point.email', $permission->email)
            ->get();

        $report->total = $orders->sum(function ($order) {
            return $order->amount + $order->fee;
        });

        $last_days = [];
        $today = today();
        $date = today()->subDays($this->last_days);
        do {
            $last_days[] = $orders->filter( function ($order, $key) use ($date) {
                return $order->created_at->isSameDay($date);
            })->sum(function ($order) {
                return $order->amount + $order->fee;
            });
            $date->addDay();
        } while ($date->lte($today));

        $report->last_days = $last_days;

        return $report;
    }
}
