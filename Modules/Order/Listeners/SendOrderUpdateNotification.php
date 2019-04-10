<?php

namespace Modules\Order\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Order\Emails\OrderApproved;
use Modules\Order\Emails\OrderCancelled;
use Modules\Order\Emails\OrderFailed;

;

use Modules\Order\Emails\OrderReversed;
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
    public function handle(StatusChanged $event)
    {
        $order = $event->getOrder();

        if (filled(optional($order->costumer)->email)) {
            $method = camel_case($event->getStatus());

            if (method_exists($this, $method)) {
                $this->$method($event->getOrder());
            }
        }
    }

    protected function paid(Order $order)
    {
        \Mail::to($order->costumer->email)->send(new OrderApproved($this->order));
    }

    protected function canceled(Order $order)
    {
        \Mail::to($order->costumer->email)->send(new OrderFailed($this->order));

    }

    protected function reversed(Order $order)
    {
        if (($order->amount + $order->fee - ($order->discount ?? 0)) > 0) {
            \Mail::to($order->costumer->email)->send(new OrderReversed($this->order));
        } else {
            \Mail::to($order->costumer->email)->send(new OrderCancelled($this->order));
        }
    }
}
