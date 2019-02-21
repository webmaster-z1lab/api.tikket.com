<?php

namespace Modules\Event\Observers;

use Modules\Event\Jobs\MakeAvailableLot;
use Modules\Event\Models\Entrance;

class EntranceObserver
{
    public function created(Entrance $entrance)
    {
        MakeAvailableLot::dispatch($entrance);
    }
}
