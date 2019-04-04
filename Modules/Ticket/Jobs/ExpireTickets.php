<?php

namespace Modules\Ticket\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Ticket\Models\Ticket;

class ExpireTickets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * @var string
     */
    private $event_id;

    /**
     * Create a new job instance.
     *
     * @param  string  $event_id
     */
    public function __construct(string $event_id)
    {
        $this->event_id = $event_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Ticket::where('status', Ticket::VALID)
            ->where('event.event_id', $this->event_id)
            ->update(['status' => Ticket::EXPIRED]);
    }
}
