<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 13/02/2019
 * Time: 16:21
 */

namespace App\Traits;


use Modules\Event\Jobs\LockEntrance;
use Modules\Event\Jobs\LockEvent;
use Modules\Event\Jobs\RunningOutLot;
use Modules\Event\Models\Entrance;

trait AvailableEntrances
{
    protected $sources = [
        Entrance::WAITING,
        Entrance::RESERVED,
        Entrance::SOLD,
    ];

    /**
     * @param \Modules\Event\Models\Entrance $entrance
     * @param string                         $source
     * @param int                            $value
     */
    public function incrementAvailable(Entrance $entrance, string $source, int $value = 1)
    {
        if (!in_array($source, $this->sources)) abort('400', 'The number added as available needs to be removed from another source of entrances: waiting, reserved or sold.');

        $this->checkAmount($entrance, $source, $value);

        $entrance->available->increment(Entrance::AVAILABLE, $value);
        $entrance->available->decrement($source, $value);
    }

    /**
     * @param \Modules\Event\Models\Entrance $entrance
     * @param int                            $value
     */
    public function incrementReserved(Entrance $entrance, int $value = 1)
    {
        $this->checkAmount($entrance, Entrance::AVAILABLE, $value);

        $entrance->available->increment(Entrance::RESERVED, $value);
        $entrance->available->decrement(Entrance::AVAILABLE, $value);
    }

    /**
     * @param \Modules\Event\Models\Entrance $entrance
     * @param int                            $value
     */
    public function incrementWaiting(Entrance $entrance, int $value = 1)
    {
        $entrance->available->increment(Entrance::WAITING, $value);
        $entrance->available->decrement(Entrance::RESERVED, $value);
    }

    /**
     * @param \Modules\Event\Models\Entrance $entrance
     * @param int                            $value
     * @param string                         $source
     */
    public function incrementSold(Entrance $entrance, int $value = 1, string $source = Entrance::WAITING)
    {
        if ($entrance->available->sold === 0) {
            LockEntrance::dispatch($entrance, $entrance->available->lot);
            LockEvent::dispatch($entrance->event);
        }

        $entrance->available->increment(Entrance::SOLD, $value);
        $entrance->available->decrement($source, $value);

        if ((($entrance->available->available * 10) <= $entrance->available->amount) && !$entrance->available->was_advised)
            RunningOutLot::dispatch($entrance);
    }

    /**
     * @param \Modules\Event\Models\Entrance $entrance
     * @param int                            $value
     */
    public function incrementAmount(Entrance $entrance, int $value = 1)
    {
        $entrance->available->increment(Entrance::AMOUNT, $value);
        $entrance->available->increment(Entrance::AVAILABLE, $value);
    }

    private function checkAmount(Entrance $entrance, string $source, int $value)
    {
        if ($entrance->available->$source < $value) abort('400', "No available entrances to move from the source $source.");
    }
}
