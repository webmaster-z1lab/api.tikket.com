<?php

namespace Modules\Cart\Policies;

use Modules\Cart\Models\Cart;
use Z1lab\OpenID\Models\User;

class CartPolicy
{
    /**
     * @param \Z1lab\OpenID\Models\User        $user
     * @param \Modules\Cart\Models\Cart|string $cart
     *
     * @return bool
     */
    public function owner(User $user, $cart)
    {
        if ($cart instanceof Cart)
            return $cart->user_id === NULL || $cart->user_id === $user->id;

        return Cart::whereKey($cart)->where('user_id', $user->id)->exists();
    }
}
