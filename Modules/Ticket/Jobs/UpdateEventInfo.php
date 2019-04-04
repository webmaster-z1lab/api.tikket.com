<?php

namespace Modules\Ticket\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Event\Models\Event;
use Modules\Ticket\Models\Ticket;

class UpdateEventInfo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \Modules\Event\Models\Event
     */
    private $event;

    /**
     * Create a new job instance.
     *
     * @param  \Modules\Event\Models\Event  $event
     */
    public function __construct(Event $event)
    {
        $this->event = $event->fresh();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tickets = Ticket::where('event.event_id', $this->event->id)->get();

        $data = [
            'name'        => $this->event->name,
            'status'      => $this->event->status,
            'url'         => $this->event->url,
            'address'     => $this->event->address->formatted,
            'starts_at'   => $this->event->starts_at,
            'finishes_at' => $this->event->finishes_at,
            'image'       => $this->event->image->toArray(),
        ];

        $tickets->each(function ($ticket) use ($data) {
            /** @var \Modules\Ticket\Models\Ticket $ticket */
            $ticket->event->update(array_except($data, 'image'));

            $ticket->event->image->update($data['image']);
        });
    }
}
