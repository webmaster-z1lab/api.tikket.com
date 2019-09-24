<?php

namespace Modules\Order\Observers;

use App\Notifications\Customer\OrderApproved;
use App\Notifications\Customer\OrderCancelled;
use App\Notifications\Customer\OrderFailed;
use App\Notifications\Customer\OrderReversed;
use Illuminate\Support\Str;
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
    public function saving(Order $order): void
    {
        if (($order->channel === Order::ONLINE_CHANNEL) && $order->isDirty('status') && filled(optional($order->customer)->email)) {
            //event(new StatusChanged($order, $order->getOriginal('status'), $order->status));
            $method = camel_case($order->status);

            if (method_exists($this, $method)) {
                $this->$method($order->fresh());
            }
        }
    }

    /**
     * @param  \Modules\Order\Models\Order  $order
     */
    protected function paid(Order $order): void
    {
        $order->notify(new OrderApproved);
    }

    /**
     * @param  \Modules\Order\Models\Order  $order
     */
    protected function canceled(Order $order): void
    {
        $order->notify(new OrderFailed);
    }

    /**
     * @param  \Modules\Order\Models\Order  $order
     */
    protected function reversed(Order $order): void
    {
        if (($order->amount + $order->fee - ($order->discount ?? 0)) > 0) {
            $order->notify(new OrderReversed);
        } else {
            $order->notify(new OrderCancelled);
        }
    }
}
