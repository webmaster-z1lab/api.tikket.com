<?php

namespace Modules\Ticket\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Order\Models\Order;
use Modules\Ticket\Repositories\TicketRepository;

class CreateTickets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \Modules\Order\Models\Order
     */
    private $order;

    /**
     * Create a new job instance.
     *
     * @param \Modules\Order\Models\Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @param \Modules\Ticket\Repositories\TicketRepository $repository
     *
     * @return void
     */
    public function handle(TicketRepository $repository)
    {
        $repository->createFromOrder($this->order->id);
    }
}
