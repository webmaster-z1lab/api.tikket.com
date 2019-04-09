<?php

namespace Modules\Event\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Event\Models\Entrance;
use Modules\Event\Models\Permission;

class RunningOutLot  implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \Modules\Event\Models\Entrance
     */
    private $entrance;

    /**
     * Create a new job instance.
     *
     * @param  \Modules\Event\Models\Entrance  $entrance
     */
    public function __construct(Entrance $entrance)
    {
        $this->entrance = $entrance;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $admins = $this->entrance->event->permissions()->whereIn('type', [Permission::ORGANIZER, Permission::MASTER])->get();

        /** @var \Modules\Event\Models\Permission $admin */
        foreach ($admins as $admin) {
            \Mail::to($admin->email)->send(new \Modules\Event\Emails\RunningOutLot($this->entrance));
        }
    }
}
