<?php

namespace Modules\Ticket\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Str;
use Modules\Order\Models\Order;
use Modules\Ticket\Models\Ticket;

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
        $this->order = $order->fresh();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $event_data = [
            'event_id'  => $this->order->event->id,
            'name'      => $this->order->event->name,
            'url'       => $this->order->event->url,
            'address'   => $this->order->event->address->formatted,
            'starts_at' => $this->order->event->starts_at,
            'image'     => $this->order->event->image->toArray(),
        ];

        $order_id = $this->order->id;

        $this->order->tickets->each(function ($participant, $key) use ($event_data, $order_id) {
            $ticket = Ticket::create([
                'name' => $participant->entrance,
                'lot'  => $participant->lot,
                'code' => $participant->code === NULL ? strtoupper(Str::random(Ticket::CODE_LENGTH)) : $participant->code,
            ]);

            $ticket->order()->associate($order_id);

            $ticket->entrance()->associate($participant->entrance_id);

            $ticket->save();

            $ticket->participant()->create([
                'name'     => $participant->name,
                'document' => $participant->document,
                'email'    => $participant->email,
            ]);

            /** @var \Modules\Ticket\Models\Event $event */
            $event = $ticket->event()->create(array_except($event_data, ['image']));

            $event->image()->create($event_data['image']);
        });
    }
}
