<?php

namespace Modules\Event\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Event\Models\Entrance;

class UpdateAvailableLot implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \Modules\Event\Models\Entrance
     */
    private $entrance;

    /**
     * Create a new job instance.
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
        $lot = $this->entrance->getLot($this->entrance->available->lot);

        $incrementAmount = $lot->amount - $this->entrance->available->amount;

        $this->entrance->available->update([
            'amount'      => $lot->amount,
            'finishes_at' => $lot->finishes_at,
        ]);

        $this->entrance->available->increment('available', $incrementAmount);
    }
}
