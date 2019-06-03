<?php

namespace Modules\Ticket\Policies;

use Modules\Ticket\Models\Ticket;
use Z1lab\OpenID\Models\User;

class TicketPolicy
{
    /**
     * @param \Z1lab\OpenID\Models\User            $user
     * @param \Modules\Ticket\Models\Ticket|string $ticket
     *
     * @return boolean
     */
    public function owner(User $user, $ticket)
    {
        if ($ticket instanceof Ticket) {
            if (\Gate::allows('admin', $ticket->event->event_id)) return TRUE;

            return \Gate::allows('order_owner', $ticket->order_id);
        }

        return Ticket::whereKey($ticket)->whereHas('order', function ($query) use ($user) {
            $query->where('customer.user_id', $user->id);
        })->exists();
    }

    /**
     * @param \Z1lab\OpenID\Models\User            $user
     * @param \Modules\Ticket\Models\Ticket|string $ticket
     *
     * @return boolean
     */
    public function receiver(User $user, $ticket)
    {
        if (\Gate::allows('ticket_owner', $ticket)) return TRUE;

        if ($ticket instanceof Ticket)
            return $ticket->participant->email === $user->email;

        return Ticket::whereKey($ticket)->where('participant.email', $user->email)->exists();
    }
}
