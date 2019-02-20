<?php

namespace Modules\Event\Console;

use Illuminate\Console\Command;
use Modules\Event\Repositories\EntranceRepository;

class UpdateLots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'entrance:update-lots';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Update the entrance's available lot if it is finished or sold out.";

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param \Modules\Event\Repositories\EntranceRepository $repository
     *
     * @return void
     */
    public function handle(EntranceRepository $repository)
    {
        $entrances = $repository->getEntrances();

        /** @var \Modules\Event\Models\Entrance $entrance */
        foreach ($entrances as $entrance) {
            /** @var \Modules\Event\Models\Lot $lot */
            $lot = $entrance->lots->firstWhere('number', $entrance->available->lot + 1);

            if ($lot !== NULL) {
                $entrance->available()->delete();

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
}
