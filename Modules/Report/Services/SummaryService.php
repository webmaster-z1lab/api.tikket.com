<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 27/03/2019
 * Time: 10:12
 */

namespace Modules\Report\Services;


use Modules\Order\Models\Order;

class SummaryService
{
    /**
     * @param string $event
     *
     * @return array
     */
    public function basic(string $event)
    {
        $online = [];

        $sales = Order::where('event_id', $event)
            ->where('status', Order::PAID)
            ->where('channel', Order::ONLINE_CHANNEL)
            ->get();

        $online['sales'] = $sales->count();

        $net_value = [];

        $net_value['online'] = $sales->sum(function ($order) {
            return $order->amount - ($order->discount ?? 0);
        });

        $online['value'] = $net_value['online'] + $sales->sum('fee');

        $manual = [];

        $sales = Order::where('event_id', $event)
            ->where('status', Order::PAID)
            ->whereIn('channel', [Order::ADMIN_CHANNEL, Order::PDV_CHANNEL])
            ->get();

        $manual['sales'] = $sales->count();

        $manual['value'] = $sales->sum(function ($order) {
            return $order->amount + $order->fee - ($order->discount ?? 0);
        });

        $total = [
            'sales' => $online['sales'] + $manual['sales'],
            'value' => $online['value'] + $manual['value']
        ];

        $net_value['total'] = $net_value['online'] + $manual['value'];

        return compact('online', 'manual', 'total', 'net_value');
    }
}
