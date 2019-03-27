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
    protected const LAST_DAYS = 6;

    /**
     * @param string $event
     * @param string $status
     *
     * @return \Modules\Report\Models\Report
     */
    public function valueSales(string $event, string $status)
    {
        $report = new Report();
        $orders = Order::where('event_id', $event)
            ->where('status', $status)
            ->get();


        $report->total = $orders->sum(function ($order) {
            return $order->amount + $order->fee - ($order->discount ?? 0);
        });

        $last_days = [];
        $today = today();
        $date = today()->subDays(self::LAST_DAYS);
        do {
            $last_days[] = $orders->filter(function ($order) use ($date) {
                return $order->created_at->isSameDay($date);
            })->sum(function ($order) {
                return $order->amount + $order->fee - ($order->discount ?? 0);
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
            ->where('channel', Order::ONLINE_CHANNEL)
            ->get();

        $report->total = $orders->sum(function ($order) {
            return $order->amount - ($order->discount ?? 0);
        });

        $last_days = [];
        $today = today();
        $date = today()->subDays(self::LAST_DAYS);
        do {
            $last_days[] = $orders->filter(function ($order) use ($date) {
                return $order->created_at->isSameDay($date);
            })->sum(function ($order) {
                return $order->amount - ($order->discount ?? 0);
            });
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
    public function numberOfSales(string $event, string $status)
    {
        $report = new Report();
        $orders = Order::where('event_id', $event)
            ->where('status', $status)
            ->get();

        $report->total = $orders->count();

        $last_days = [];
        $today = today();
        $date = today()->subDays(self::LAST_DAYS);
        do {
            $last_days[] = $orders->filter(function ($order) use ($date) {
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
        $date = today()->subDays(self::LAST_DAYS);
        do {
            $last_days[] = $orders->filter(function ($order) use ($date) {
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
     * @param string   $event
     * @param string   $pdv
     * @param callable $sum
     *
     * @return \Modules\Report\Models\Report
     */
    public function salePointReports(string $event, string $pdv, callable $sum)
    {
        $permission = Permission::whereKey($pdv)->where('event_id', $event)->first();

        if ($permission === NULL) abort(404);

        $report = new Report();

        $orders = Order::where('event_id', $event)
            ->where('channel', Order::PDV_CHANNEL)
            ->where('sale_point.email', $permission->email)
            ->get();

        $report->total = $orders->sum($sum);

        $last_days = [];
        $today = today();
        $date = today()->subDays(self::LAST_DAYS);
        do {
            $last_days[] = $orders->filter(function ($order) use ($date) {
                return $order->created_at->isSameDay($date);
            })->sum($sum);
            $date->addDay();
        } while ($date->lte($today));

        $report->last_days = $last_days;

        return $report;
    }
}
