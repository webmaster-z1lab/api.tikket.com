<?php

namespace Modules\Event\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Event\Models\Event;
use Modules\Event\Models\Permission;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * @param \Z1lab\OpenID\Models\User          $user
     * @param \Modules\Event\Models\Event|string $event
     *
     * @return bool
     */
    public function master(\Z1lab\OpenID\Models\User $user, $event)
    {
        $event = $this->getEvent($event);

        return $event->permissions()
            ->where('email', $user->email)
            ->where('type', Permission::MASTER)
            ->exists();
    }

    /**
     * @param \Z1lab\OpenID\Models\User          $user
     * @param \Modules\Event\Models\Event|string $event
     *
     * @return bool
     */
    public function organizer(\Z1lab\OpenID\Models\User $user, $event)
    {
        $event = $this->getEvent($event);

        return $event->permissions()
            ->where('email', $user->email)
            ->where('type', Permission::ORGANIZER)
            ->exists();
    }

    /**
     * @param \Z1lab\OpenID\Models\User          $user
     * @param \Modules\Event\Models\Event|string $event
     *
     * @return bool
     */
    public function checkin(\Z1lab\OpenID\Models\User $user, $event)
    {
        $event = $this->getEvent($event);

        return $event->permissions()
            ->where('email', $user->email)
            ->where('type', Permission::CHECKIN)
            ->exists();
    }

    /**
     * @param \Z1lab\OpenID\Models\User          $user
     * @param \Modules\Event\Models\Event|string $event
     *
     * @return bool
     */
    public function pdv(\Z1lab\OpenID\Models\User $user, $event)
    {
        $event = $this->getEvent($event);

        return $event->permissions()
            ->where('email', $user->email)
            ->where('type', Permission::PDV)
            ->exists();
    }

    /**
     * @param $event
     *
     * @return \Modules\Event\Models\Event
     */
    private function getEvent($event)
    {
        if ($event instanceof Event) return $event;

        return Event::find($event);
    }
}
