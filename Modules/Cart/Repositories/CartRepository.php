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
     * @return \Illuminate\Database\Eloquent\Model|\Modules\Cart\Models\Cart|object|null
     */
    public function getByUser()
    {
        return Cart::where('user_id', \Auth::user()->id)->first();
    }

    /**
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model|\Modules\Cart\Models\Cart|null
     */
    public function create(array $data)
    {
        $cart = Cart::create([
            'user_id'    => \Auth::user()->id,
            'event_id'   => $data['event_id'],
            'callback'   => $data['callback'],
            'expires_at' => now()->addMinutes(15),
        ]);

        $amount = 0;
        foreach ($data['items'] as $item) {
            $item['quantity'] = intval($item['quantity']);
            $entrance = Entrance::find($item['id']);
            $price = $entrance->lots()->number($item['lot'])->first()->value;
            $amount += ($item['quantity'] * $price);
            for ($aux = 0; $aux < $item['quantity']; $aux++) {
                $cart->tickets()->create([
                    'entrance_id' => $item['id'],
                    'entrance'    => $entrance->name,
                    'lot'         => $item['lot'],
                    'price'       => $price,
                ]);
            }
        }

        $cart->amount = $amount;
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
        $cart = Cart::find($id);

        if ($cart === NULL)
            abort(404);

        foreach ($data['tickets'] as $ticket) {
            $item = $cart->tickets()->find($ticket['id']);
            $item->update(array_except($ticket, ['id']));
        }

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
        $cart = Cart::find($id);

        if ($cart === NULL)
            abort(404);

        $cart->update([
            'hash'     => $data['hash'],
            'callback' => $data['callback'],
        ]);

        $data['card']['parcel'] = (int)(floatval($data['card']['parcel']) * 100);

        $card = $cart->card()->create(array_except($data['card'], ['holder']));

        $holder = $card->holder()->create(array_except($data['card']['holder'], ['address']));

        $holder->address()->create($data['card']['holder']['address']);

        $holder->save();

        $card->save();

        $cart->save();

        return $cart->fresh();
    }
}
