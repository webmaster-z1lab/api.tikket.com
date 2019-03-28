<?php

namespace Modules\Order\Policies;

use Modules\Order\Models\Order;
use Z1lab\OpenID\Models\User;

class OrderPolicy
{
    /**
     * @param \Z1lab\OpenID\Models\User          $user
     * @param \Modules\Order\Models\Order|string $order
     *
     * @return bool
     */
    public function owner(User $user, $order)
    {
        if ($order instanceof Order) {
            if (\Gate::allows('admin', $order->event_id)) return TRUE;

            return optional($order->costumer)->user_id === $user->id;
        }

        $order = Order::whereKey($order)->first();

        if ($order === NULL) abort(404);

        if (\Gate::allows('admin', $order->event_id)) return TRUE;

        return optional($order->costumer)->user_id === $user->id;
    }
}
