<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 30/01/2019
 * Time: 15:48
 */

namespace Modules\Cart\Repositories;

use App\Traits\AvailableEntrances;
use Modules\Cart\Events\UserInformationReceived;
use Modules\Cart\Jobs\RecycleCart;
use Modules\Cart\Models\Cart;
use Modules\Event\Models\Coupon;
use Modules\Event\Models\Entrance;
use Z1lab\OpenID\Services\ApiService;

class CartRepository
{
    use AvailableEntrances;

    /**
     * @param string $id
     *
     * @return null|\Modules\Cart\Models\Cart
     */
    public function find(string $id)
    {
        $cart = Cart::where('_id', $id)->active()->first();

        if ($cart === NULL) abort(404);

        return $cart;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|\Modules\Cart\Models\Cart|null
     */
    public function getByUser()
    {
        $cart = Cart::where('user_id', \Auth::id())->active()->latest()->first();

        if(null === $cart) abort(404);

        return $cart;
    }

    /**
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model|\Modules\Cart\Models\Cart|null
     */
    public function create(array $data)
    {
        $cart = Cart::create([
            'user_id'  => \Auth::check() ? \Auth::id() : NULL,
            'callback' => $data['callback'],
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

            for ($i = 0; $i < $ticket['quantity']; $i++) {
                $cart->tickets()->create([
                    'entrance_id' => $ticket['entrance'],
                    'entrance'    => $entrance->name,
                    'lot'         => $ticket['lot'],
                    'value'       => $lot->value,
                    'fee'         => $lot->fee,
                ]);
            }

            $cart->bags()->create([
                'entrance_id' => $ticket['entrance'],
                'amount'      => $ticket['quantity'],
            ]);

            $this->incrementReserved($entrance, $ticket['quantity']);
        }

        $cart->amount = $amount;
        $cart->fee = $fee;
        $cart->fee_percentage = $cart->event->fee_percentage;
        $cart->fee_is_hidden = $cart->event->fee_is_hidden;
        $cart->expires_at = now()->addMinutes(14)->addSeconds(2);
        $cart->save();

        RecycleCart::dispatch($cart)->delay($cart->expires_at->addSeconds(58));

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
        if ($cart->user_id === NULL) $cart->user_id = \Auth::id();

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

        $holder->phone()->create([
            'area_code' => substr($data['card']['holder']['phone'], 0, 2),
            'phone'     => substr($data['card']['holder']['phone'], 2),
        ]);

        $holder->save();

        $card->save();

        if (array_key_exists('costumer', $data)) {
            $costumer = $cart->costumer()->create(['document' => $data['costumer']['document']]);

            $costumer->phone()->create([
                'area_code' => substr($data['costumer']['phone'], 0, 2),
                'phone'     => substr($data['costumer']['phone'], 2),
            ]);

            $costumer->save();

            event(new UserInformationReceived(\Request::bearerToken(), \Auth::id(), $data['costumer']['document'], $data['costumer']['phone']));
        } else {
            $costumer = $cart->costumer()->create(['document' => \Auth::user()->document]);
            $user = (new ApiService())->getUser(\Request::bearerToken())->data;

            $costumer->phone()->create([
                'area_code' => $user->attributes->phone->area_code,
                'phone'     => $user->attributes->phone->phone,
            ]);

            $costumer->save();
        }

        $cart->save();

        return $cart->fresh();
    }

    /**
     * @param string $coupon
     * @param string $id
     *
     * @return \Modules\Cart\Models\Cart|null
     */
    public function applyCoupon(string $coupon, string $id)
    {
        $cart = $this->find($id);

        $entrances = $cart->tickets->pluck('entrance_id')->toArray();

        $coupon = Coupon::where('code', $coupon)->whereIn('entrance_id', $entrances)->first();

        if ($coupon === NULL) abort(404);

        if ($coupon->is_percentage) {
            $ticketWillDiscount = $cart->tickets()->where('entrance_id', $coupon->entrance_id)->first();
            $cart->discount = (int) ($ticketWillDiscount * $coupon->discount / 100);
        } else {
            $cart->discount = $coupon->discount;
        }

        $cart->coupon()->associate($coupon);

        $cart->save();

        return $cart->fresh();
    }
}
