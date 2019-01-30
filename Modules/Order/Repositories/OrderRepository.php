<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 29/01/2019
 * Time: 18:42
 */

namespace Modules\Order\Repositories;

use Modules\Cart\Models\Cart;
use Modules\Order\Models\Order;

class OrderRepository
{
    /**
     * @param string $id
     *
     * @return null|\Modules\Cart\Models\Cart
     */
    public function find(string $id)
    {
        $cart = Cart::find($id);

        if ($cart === NULL)
            abort(404);

        return $cart;
    }

    public function createByCart(string $cart_id, string $ip)
    {
        $cart = $this->find($cart_id);

        $cart = $cart->toArray();

        $order = Order::create([
            'hash' => $cart['hash'],
            'ip'   => $ip,
            'type' => $cart['type'],
        ]);

        $order->tickets()->createMany($cart['tickets']);

        $user = \Auth::user();

        $costumer = $order->costumer()->create([
            'user_id'  => $user->id,
            'name'     => $user->name,
            'email'    => $user->email,
            'document' => $user->document,
        ]);
        $costumer->phone()->create($cart['card']['holder']['phone']);
        $costumer->save();

        $card = $order->card()->create(array_except($cart['card'], ['holder']));
        $holder = $card->holder()->create(array_except($cart['card']['holder'], ['address', 'phone']));
        $holder->phone()->create($cart['card']['holder']['phone']);
        $holder->address()->create($cart['card']['holder']['address']);
        $holder->save();
        $card->save();

        $order->save();

        return $order->save();
    }
}
