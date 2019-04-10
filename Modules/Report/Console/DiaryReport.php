<?php

namespace Modules\Report\Console;

use Illuminate\Console\Command;
use Modules\Event\Models\Event;
use Modules\Event\Models\Permission;

class DiaryReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:diary-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Send e-mail to the organization warning about a lot that is running out.";

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $events;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->events = Event::where('status', Event::PUBLISHED)->get();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /** @var \Modules\Event\Models\Event $event */
        foreach ($this->events as $event) {
            $admins = $event->permissions()->whereIn('type', [Permission::ORGANIZER, Permission::MASTER])->get();

            /** @var \Modules\Event\Models\Permission $admin */
            foreach ($admins as $admin) {
                \Mail::to($admin->email)->send(new \Modules\Report\Emails\DiaryReport($event));
            }
        }
    }
}
