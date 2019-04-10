<?php

namespace Modules\Event\Console;

use Illuminate\Console\Command;
use Modules\Event\Emails\EventSoon;
use Modules\Event\Models\Event;
use Modules\Ticket\Models\Ticket;

class UpcomingEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:upcoming';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Send e-mail to the participants of an upcoming event.";

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $tickets;

    public const DAYS = 3;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $start = today()->addDays(self::DAYS)->startOfDay();
        $end = today()->addDays(self::DAYS)->endOfDay();

        $this->tickets = Ticket::where('status', Ticket::VALID)
            ->whereNotNull('participant.email')
            ->whereBetween('event.starts_at', [$start, $end])
            ->get();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /** @var \Modules\Ticket\Models\Ticket $ticket */
        foreach ($this->tickets as $ticket) {
            if (filled($ticket->participant->email))
                \Mail::to($ticket->participant->email)->send(new EventSoon(Event::find($ticket->event->event_id), $ticket));
        }
    }
}
