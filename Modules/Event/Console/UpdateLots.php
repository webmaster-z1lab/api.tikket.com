<?php

namespace Modules\Event\Console;

use Illuminate\Console\Command;
use Modules\Event\Emails\LotChanged;
use Modules\Event\Models\Entrance;
use Modules\Event\Models\Lot;
use Modules\Event\Models\Permission;

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
        parent::__construct();

        $this->entrances = Entrance::orExpired()->orSoldOut()->get();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (NULL !== $this->entrances) {
            /**
             * @var Entrance $entrance
             */
            foreach ($this->entrances as $entrance) {
                $current = $entrance->getLot($entrance->available->lot);
                $next = $entrance->getLot($entrance->available->lot + 1);

                $current->available = $entrance->available->available;
                $current->reserved = $entrance->available->reserved;
                $current->waiting = $entrance->available->waiting;
                $current->sold = $entrance->available->sold;
                $current->status = $entrance->available->isSoldOut() ? Lot::EXPIRED : Lot::CLOSED;

                $current->save();

                if (NULL !== $next) {
                    $entrance->available()->delete();

                    $entrance->available()->create([
                        'lot_id'      => $next->_id,
                        'lot'         => $next->number,
                        'available'   => $next->amount + $current->available,
                        'amount'      => $next->amount,
                        'remainder'   => $current->available,
                        'value'       => $next->value,
                        'fee'         => $next->fee,
                        'price'       => $next->value + $next->fee,
                        'starts_at'   => $current->finishes_at->addSecond(),
                        'finishes_at' => $next->finishes_at,
                    ]);

                    $admins = $entrance->event->permissions()->whereIn('type', [Permission::ORGANIZER, Permission::MASTER])->get();
                    /** @var \Modules\Event\Models\Permission $admin */
                    foreach ($admins as $admin) {
                        \Mail::to($admin->email)->send(new LotChanged($entrance));
                    }
                }
            }
        }
    }
}
