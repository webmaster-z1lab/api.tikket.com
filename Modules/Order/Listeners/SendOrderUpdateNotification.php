<?php

namespace Modules\Order\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Order\Emails\OrderApproved;
use Modules\Order\Emails\OrderFailed;;

use Modules\Order\Emails\OrderReversed;
use Modules\Order\Events\StatusChanged;

class SendOrderUpdateNotification implements ShouldQueue
{
    /**
     * @var \Modules\Order\Models\Order
     */
    private $order;

    /**
     * Handle the event.
     *
     * @param  StatusChanged $event
     *
     * @return void
     */
    public function handle(StatusChanged $event)
    {
        if (NULL !== $event->getOldStatus()) {
            $this->order = $event->getOrder();

            $method = camel_case($event->getStatus());

            if (method_exists($this, $method)) $this->$method($event->getOldStatus());
        }
    }

    /**
     * @param string $oldStatus
     */
    protected function active(string $oldStatus)
    {
        \Mail::to($this->order->costumer->email)->send(new OrderApproved($this->order));
    }

    /**
     * @param string $oldStatus
     */
    protected function canceled(string $oldStatus)
    {
        \Mail::to($this->order->costumer->email)->send(new OrderFailed($this->order));

    }

    /**
     * @param string $oldStatus
     */
    protected function reversed(string $oldStatus)
    {
        \Mail::to($this->order->costumer->email)->send(new OrderReversed($this->order));
    }
}
