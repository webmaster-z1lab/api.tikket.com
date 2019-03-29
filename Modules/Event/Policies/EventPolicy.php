<?php

namespace Modules\Event\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Event\Models\Event;
use Modules\Event\Models\Permission;
use Z1lab\OpenID\Models\User;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * @param \Z1lab\OpenID\Models\User          $user
     * @param \Modules\Event\Models\Event|string $event
     *
     * @return bool
     */
    public function master(User $user, $event)
    {
        return $this->checkType($user, $event, Permission::MASTER);
    }

    /**
     * @param \Z1lab\OpenID\Models\User          $user
     * @param \Modules\Event\Models\Event|string $event
     *
     * @return bool
     */
    public function organizer(User $user, $event)
    {
        return $this->checkType($user, $event, Permission::ORGANIZER);
    }

    /**
     * @param \Z1lab\OpenID\Models\User          $user
     * @param \Modules\Event\Models\Event|string $event
     *
     * @return bool
     */
    public function checkin(User $user, $event)
    {
        return $this->checkType($user, $event, Permission::CHECKIN);
    }

    /**
     * @param \Z1lab\OpenID\Models\User          $user
     * @param \Modules\Event\Models\Event|string $event
     *
     * @return bool
     */
    public function pdv(User $user, $event)
    {
        return $this->checkType($user, $event, Permission::PDV);
    }

    /**
     * @param \Z1lab\OpenID\Models\User $user
     * @param                           $event
     *
     * @return bool
     */
    public function sell(User $user, $event)
    {
        if ($event instanceof Event)
            return $event->permissions()->where('email', $user->email)
                ->whereIn('type', [Permission::MASTER, Permission::ORGANIZER, Permission::PDV])
                ->exists();

        return Permission::where('event_id', $event)
            ->where('email', $user->email)
            ->whereIn('type', [Permission::MASTER, Permission::ORGANIZER, Permission::PDV])
            ->exists();
    }

    /**
     * @param \Z1lab\OpenID\Models\User $user
     * @param                           $event
     *
     * @return bool
     */
    public function admin(User $user, $event)
    {
        if ($event instanceof Event)
            return $event->permissions()->where('email', $user->email)
                ->whereIn('type', [Permission::MASTER, Permission::ORGANIZER])
                ->exists();

        return Permission::where('event_id', $event)
            ->where('email', $user->email)
            ->whereIn('type', [Permission::MASTER, Permission::ORGANIZER])
            ->exists();
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
