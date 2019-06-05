<?php

Broadcast::channel('orders.{order}', function ($user, \Modules\Order\Models\Order $order) {
    return $order->customer->user_id === $user->id;
});
