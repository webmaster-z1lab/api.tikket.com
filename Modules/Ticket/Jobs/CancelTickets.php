<?php

namespace Modules\Ticket\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Order\Models\Order;
use Modules\Ticket\Emails\CancelledTicket;
use Modules\Ticket\Models\Ticket;

class CancelTickets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \Modules\Order\Models\Order
     */
    private $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        Ticket::where('order_id', $this->order->id)->update(['status' => Ticket::CANCELLED]);

        $tickets = Ticket::where('order_id', $this->order->id)->get();

        /** @var \Modules\Ticket\Models\Ticket $ticket */
        foreach ($tickets as $ticket) {
            if (filled(optional($ticket->participant)->email) && $ticket->participant->email !== optional($ticket->order->costumer)->email)
                \Mail::to($ticket->participant->email)->send(new CancelledTicket($ticket));
        }
    }
}
