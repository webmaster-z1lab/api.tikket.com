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

    /**
     * @param string $cart_id
     * @param string $ip
     *
     * @return \Illuminate\Database\Eloquent\Model|\Modules\Order\Models\Order|null
     * @throws \Exception
     */
    public function createByCart(string $cart_id, string $ip)
    {
        $cart = $this->find($cart_id);

        $data = $cart->toArray();

        $order = Order::create([
            'hash'   => $data['hash'],
            'ip'     => $ip,
            'type'   => $data['type'],
            'amount' => $data['amount'],
            'fee'    => $data['fee'],
        ]);

        $order->tickets()->createMany($data['tickets']);

        $user = \Auth::user();

        $costumer = $order->costumer()->create([
            'user_id'  => $user->id,
            'name'     => $user->name,
            'email'    => $user->email,
            'document' => $user->document,
        ]);
        $costumer->phone()->create($data['card']['holder']['phone']);
        $costumer->save();

        $card = $order->card()->create(array_except($data['card'], ['holder']));
        $holder = $card->holder()->create(array_except($data['card']['holder'], ['address', 'phone']));
        $holder->phone()->create($data['card']['holder']['phone']);
        $holder->address()->create($data['card']['holder']['address']);
        $holder->save();
        $card->save();

        $order->save();

        $cart->delete();

        return $order->fresh();
    }
}
