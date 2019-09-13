<?php

namespace Modules\Order\Observers;

use App\Notifications\Customer\OrderReceived;
use Illuminate\Support\Str;
use Modules\Order\Events\StatusChanged;
use Modules\Order\Models\Order;

class OrderObserver
{
    /**
     * @param  \Modules\Order\Models\Order  $order
     */
    public function creating(Order $order): void
    {
        $order->code = strtoupper(Str::random(Order::CODE_LENGTH));
    }

    /**
     * @param  \Modules\Order\Models\Order  $order
     */
    public function created(Order $order): void
    {
        if (NULL !== $order->customer && $order->type === 'credit_card') {
            $order->customer->notify(new OrderReceived($order));
        }
    }

    /**
     * @param  \Modules\Order\Models\Order  $order
     */
    public function saving(Order $order): void
    {
        if (($order->channel === Order::ONLINE_CHANNEL) && $order->isDirty('status')) {
            event(new StatusChanged($order, $order->getOriginal('status'), $order->status));
        }
    }
}
