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
        return $this->checkType($user, $event, Permission::MASTER);
    }

    /**
     * @param \Z1lab\OpenID\Models\User          $user
     * @param \Modules\Event\Models\Event|string $event
     *
     * @return bool
     */
    public function organizer(\Z1lab\OpenID\Models\User $user, $event)
    {
        return $this->checkType($user, $event, Permission::ORGANIZER);
    }

    /**
     * @param \Z1lab\OpenID\Models\User          $user
     * @param \Modules\Event\Models\Event|string $event
     *
     * @return bool
     */
    public function checkin(\Z1lab\OpenID\Models\User $user, $event)
    {
        return $this->checkType($user, $event, Permission::CHECKIN);
    }

    /**
     * @param \Z1lab\OpenID\Models\User          $user
     * @param \Modules\Event\Models\Event|string $event
     *
     * @return bool
     */
    public function pdv(\Z1lab\OpenID\Models\User $user, $event)
    {
        return $this->checkType($user, $event, Permission::PDV);
    }

    /**
     * @param \Z1lab\OpenID\Models\User          $user
     * @param \Modules\Event\Models\Event|string $event
     * @param string                             $type
     *
     * @return bool
     */
    private function checkType(\Z1lab\OpenID\Models\User $user, $event, string $type)
    {
        if ($event instanceof Event)
            return $event->permissions()->where('email', $user->email)
                ->where('type', $type)
                ->exists();

        return Permission::where('event_id', $event)
            ->where('email', $user->email)
            ->where('type', $type)
            ->exists();
    }
}
