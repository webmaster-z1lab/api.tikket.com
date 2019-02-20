<?php

namespace Modules\Event\Observers;

use Modules\Event\Jobs\MakeAvailableLot;
use Modules\Event\Models\Event;

class EventObserver
{
    public function created(Event $event)
    {
        MakeAvailableLot::dispatch($event);
    }
}
