<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 30/01/2019
 * Time: 15:48
 */

namespace Modules\Cart\Repositories;


use Modules\Cart\Models\Cart;
use Modules\Event\Models\Entrance;

class CartRepository
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
     * @return \Illuminate\Database\Eloquent\Model|\Modules\Cart\Models\Cart|object|null
     */
    public function getByUser()
    {
        return Cart::where('user_id', \Auth::id())->latest()->first();
    }

    /**
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model|\Modules\Cart\Models\Cart|null
     */
    public function create(array $data)
    {
        $cart = Cart::create([
            'user_id'    => \Auth::check() ? \Auth::id() : NULL,
            'callback'   => $data['callback'],
            'expires_at' => now()->addMinutes(15),
        ]);

        $cart->event()->associate($data['event']);

        $amount = 0;
        $fee = 0;
        foreach ($data['tickets'] as $ticket) {
            $ticket['quantity'] = intval($ticket['quantity']);
            $entrance = Entrance::find($ticket['entrance']);
            $lot = $entrance->lots()->where('number', $ticket['lot'])->first();
            $amount += ($ticket['quantity'] * $lot->value);
            $fee += ($ticket['quantity'] * $lot->fee);

            for ($aux = 0; $aux < $ticket['quantity']; $aux++) {
                $cart->tickets()->create([
                    'entrance_id' => $ticket['entrance'],
                    'entrance'    => $entrance->name,
                    'lot'         => $ticket['lot'],
                    'price'       => $lot->value,
                    'fee'         => $lot->fee,
                ]);
            }
        }

        $cart->amount = $amount;
        $cart->fee = $fee;
        $cart->save();

        return $cart->fresh();
    }

    /**
     * @param string $id
     *
     * @return \Modules\Cart\Models\Cart|null
     */
    public function setUser(string $id)
    {
        $cart = $this->find($id);

        $cart->user_id = \Auth::id();
        $cart->save();

        return $cart->fresh();
    }

    /**
     * @param array  $data
     * @param string $id
     *
     * @return \Modules\Cart\Models\Cart|null
     */
    public function setTickets(array $data, string $id)
    {
        $cart = $this->find($id);

        foreach ($data['tickets'] as $ticket) {
            $item = $cart->tickets()->find($ticket['id']);
            $item->update(array_except($ticket, ['id']));
        }

        $cart->callback = $data['callback'];
        if ($cart->user_id === NULL)
            $cart->user_id = \Auth::id();

        $cart->save();

        return $cart->fresh();
    }

    /**
     * @param array  $data
     * @param string $id
     *
     * @return \Modules\Cart\Models\Cart|null
     */
    public function setCard(array $data, string $id)
    {
        $cart = $this->find($id);

        $cart->update([
            'hash'     => $data['hash'],
            'callback' => $data['callback'],
        ]);

        $data['card']['parcel'] = (int)(floatval($data['card']['parcel']) * 100);

        $card = $cart->card()->create(array_except($data['card'], ['holder']));

        $holder = $card->holder()->create(array_except($data['card']['holder'], ['address', 'phone']));

        $holder->address()->create($data['card']['holder']['address']);
        $holder->phone()->create($data['card']['holder']['phone']);

        $holder->save();

        $card->save();

        $cart->save();

        return $cart->fresh();
    }
}
