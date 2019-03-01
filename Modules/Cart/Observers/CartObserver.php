<?php

namespace Modules\Cart\Observers;

use Modules\Cart\Models\Cart;

class CartObserver
{
    /**
     * @param \Modules\Cart\Models\Cart $cart
     */
    public function saving(Cart $cart)
    {
        if ($cart->isDirty(['amount', 'fee', 'discount']))
            $cart->update(['is_free' => $cart->discount === ($cart->amount + $cart->fee)]);
    }
}
