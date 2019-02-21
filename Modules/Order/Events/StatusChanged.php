<?php

namespace Modules\Order\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Order\Models\Order;

class StatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var \Modules\Order\Models\Order
     */
    private $order;

    /**
     * @var string
     */
    private $oldStatus;

    /**
     * @var string
     */
    private $status;

    /**
     * StatusChanged constructor.
     *
     * @param Order  $order
     * @param string $oldStatus
     * @param string $status
     */
    public function __construct(Order $order, string $oldStatus, string $status)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->status = $status;
    }

    /**
     * @return \Modules\Order\Models\Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * @return string
     */
    public function getOldStatus(): string
    {
        return $this->oldStatus;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
}
