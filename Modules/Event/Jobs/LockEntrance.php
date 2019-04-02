<?php

namespace Modules\Event\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Event\Models\Entrance;

class LockEntrance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var \Modules\Event\Models\Entrance|null
     */
    protected $entrance;
    /**
     * @var int
     */
    protected $lot;

    /**
     * LockEntrance constructor.
     *
     * @param \Modules\Event\Models\Entrance $entrance
     * @param int                            $lot
     */
    public function __construct(Entrance $entrance, int $lot)
    {
        $this->entrance = $entrance->fresh();
        $this->lot = $lot;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $lot = $this->entrance->getLot($this->lot);
        $lot->status = $lot::CLOSED;
        $this->entrance->is_locked = TRUE;

        $lot->save();
        $this->entrance->save();
    }
}
