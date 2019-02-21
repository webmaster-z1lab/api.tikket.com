<?php

namespace Modules\Event\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Event\Models\Event;

class LockEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var \Modules\Event\Models\Event
     */
    protected $event;

    /**
     * LockEvent constructor.
     *
     * @param \Modules\Event\Models\Event $event
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->event->is_locked = TRUE;
        $this->event->save();
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
