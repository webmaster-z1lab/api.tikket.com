<?php

namespace Modules\Event\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Event\Models\Event;

class MakeAvailableLot implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \Modules\Event\Models\Event
     */
    protected $event;

    /**
     * MakeAvailableLot constructor.
     *
     * @param \Modules\Event\Models\Event $event
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
        foreach ($this->event->entrances as $entrance) {
            $lot = $entrance->lots->firstWhere('number', 1);

            $entrance->available()->create([
                'lot_id'      => $lot->_id,
                'lot'         => $lot->number,
                'available'   => $lot->amount,
                'amount'      => $lot->amount,
                'value'       => $lot->value,
                'fee'         => $lot->fee,
                'price'       => $lot->value + $lot->fee,
                'starts_at'   => $entrance->starts_at,
                'finishes_at' => $lot->finishes_at,
            ]);
        }
    }
}
