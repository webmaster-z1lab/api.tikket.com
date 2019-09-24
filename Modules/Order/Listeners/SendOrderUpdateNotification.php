<?php

namespace Modules\Order\Listeners;

use App\Notifications\Customer\OrderApproved;
use App\Notifications\Customer\OrderCancelled;
use App\Notifications\Customer\OrderFailed;
use App\Notifications\Customer\OrderReversed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Order\Events\StatusChanged;
use Modules\Order\Models\Order;

class SendOrderUpdateNotification implements ShouldQueue
{
    /**
     * @var \Modules\Order\Models\Order
     */
    private $order;

    /**
     * Handle the event.
     *
     * @param  StatusChanged  $event
     *
     * @return void
     */
    public function handle(StatusChanged $event): void
    {
        $order = $event->getOrder();

        if (filled(optional($order->customer)->email)) {
            $method = camel_case($event->getStatus());

            if (method_exists($this, $method)) {
                $this->$method($event->getOrder());
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
