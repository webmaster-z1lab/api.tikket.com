<?php

namespace Modules\Order\Observers;

use Modules\Order\Events\StatusChanged;
use Modules\Order\Models\Order;

class OrderObserver
{
    /**
     * @param \Modules\Order\Models\Order $order
     */
    public function saving(Order $order)
    {
        if ($order->isDirty('status') && $order->channel === Order::ONLINE_CHANNEL) event(new StatusChanged($order, $order->getOriginal('status'), $order->status));
    }
}
