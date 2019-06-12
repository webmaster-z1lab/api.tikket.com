<?php

namespace Modules\Event\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Event\Models\Entrance;

class MakeAvailableLot implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \Modules\Event\Models\Event
     */
    protected $entrance;

    /**
     * MakeAvailableLot constructor.
     *
     * @param \Modules\Event\Models\Entrance $entrance
     */
    public function __construct(Entrance $entrance)
    {
        $this->entrance = $entrance->fresh();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $lot = $this->entrance->lots->firstWhere('number', 1);

        $this->entrance->available()->create([
            'lot_id'      => $lot->_id,
            'lot'         => $lot->number,
            'available'   => $lot->amount,
            'amount'      => $lot->amount,
            'value'       => $lot->value,
            'fee'         => $lot->fee,
            'price'       => $lot->value,
            'starts_at'   => $this->entrance->starts_at,
            'finishes_at' => $lot->finishes_at,
        ]);
    }

    /**
     * The job failed to process.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function failed(\Exception $e)
    {
        \Log::error($e->getMessage());
    }
}
