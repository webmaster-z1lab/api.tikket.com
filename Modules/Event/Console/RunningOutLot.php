<?php

namespace Modules\Event\Console;

use Illuminate\Console\Command;
use Modules\Event\Models\Entrance;

class RunningOutLot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'entrance:running-out-lot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Send e-mail to the organization warning about a lot that is running out.";

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $entrances;

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

        $this->entrances = Entrance::whereBetween('available.finishes_at', [$start, $end])->get();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /** @var \Modules\Event\Models\Entrance $ticket */
        foreach ($this->entrances as $entrance) {
            \Modules\Event\Jobs\RunningOutLot::dispatch($entrance);
        }
    }
}
