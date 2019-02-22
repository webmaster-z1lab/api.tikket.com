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
        $this->lot = $this->entrance->getLot($lot);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->lot->status = $this->lot::CLOSED;
        $this->entrance->is_locked = TRUE;

        $this->lot->save();
        $this->entrance->save();
    }

    /**
     * The job failed to process.
     *
     * @param  \Exception $e
     *
     * @return void
     */
    public function failed(\Exception $e)
    {
        \Log::error($e->getMessage());
    }
}
