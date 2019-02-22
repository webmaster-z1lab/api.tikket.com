<?php

namespace Modules\Event\Console;

use Illuminate\Console\Command;
use Modules\Event\Models\Entrance;

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
     * @var \Illuminate\Support\Collection
     */
    protected $entrances;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        $this->entrances = Entrance::expired()->soldOut()->get();

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        /**
         * @var Entrance $entrance
         */
        foreach ($this->entrances as $entrance) {
            $lot = $entrance->getLot($entrance->available->lot + 1);

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
