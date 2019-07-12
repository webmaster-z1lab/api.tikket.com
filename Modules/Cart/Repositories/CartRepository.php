<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 30/01/2019
 * Time: 15:48
 */

namespace Modules\Cart\Repositories;

use App\Traits\AvailableCoupons;
use App\Traits\AvailableEntrances;
use Modules\Cart\Events\UserInformationReceived;
use Modules\Cart\Jobs\RecycleCart;
use Modules\Cart\Jobs\UpdateUserAddress;
use Modules\Cart\Models\Cart;
use Modules\Event\Models\Coupon;
use Modules\Event\Models\Entrance;
use Z1lab\OpenID\Services\ApiService;

class CartRepository
{
    use AvailableEntrances, AvailableCoupons;

    /**
     * @param  string  $id
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

        if (NULL === $cart) abort(404);

        return $cart;
    }

    /**
     * @param  array  $data
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

            if (!$entrance->is_free) {
                $amount += ($ticket['quantity'] * $lot->value);
                $fee += ($ticket['quantity'] * $lot->fee);
            }

            for ($i = 0; $i < $ticket['quantity']; $i++) {
                $cart->tickets()->create([
                    'entrance_id' => $ticket['entrance'],
                    'entrance'    => $entrance->name,
                    'lot'         => $ticket['lot'],
                    'value'       => $entrance->is_free ? 0 : $lot->value,
                    'fee'         => $entrance->is_free ? 0 : $lot->fee,
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
     * @param  array   $data
     * @param  string  $id
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
     * @param  array   $data
     * @param  string  $id
     *
     * @return \Modules\Cart\Models\Cart|null
     */
    public function setPayment(array $data, string $id)
    {
        $cart = $this->find($id);

        $cart->update([
            'hash'     => $data['hash'],
            'callback' => $data['callback'],
            'type'     => $data['type'],
        ]);

        if (array_key_exists('customer', $data) && filled($data['customer']['document']) && filled($data['customer']['phone'])) {
            $customer = $cart->customer()->create(['document' => $data['customer']['document']]);

            $customer->phone()->create([
                'area_code' => substr($data['customer']['phone'], 0, 2),
                'phone'     => substr($data['customer']['phone'], 2),
            ]);

            $customer->save();

            event(new UserInformationReceived(\Request::bearerToken(), \Auth::id(), $data['customer']['document'], $data['customer']['phone']));
        } else {
            $customer = $cart->customer()->create(['document' => \Auth::user()->document]);

            $user = (new ApiService())->getUser(\Request::bearerToken())->data;

            $customer->phone()->create([
                'area_code' => $user->attributes->phone->area_code,
                'phone'     => $user->attributes->phone->phone,
            ]);
        }

        $cart->save();

        switch ($data['type']) {
            case 'credit_card':
                $this->setCardPayment($data, $cart);
                break;
            case 'boleto':
                $this->setBoletoPayment($data, $cart);
        }

        return $cart->fresh();
    }

    /**
     * @param  string  $coupon
     * @param  string  $id
     *
     * @return \Modules\Cart\Models\Cart|null
     */
    public function applyCoupon(string $coupon, string $id)
    {
        $cart = $this->find($id);

        $entrances = $cart->tickets->pluck('entrance_id')->toArray();

        $coupon = Coupon::where('code', $coupon)->whereIn('entrance_id', $entrances)->first();

        if ($coupon === NULL) abort(404);

        $this->incrementUsed($coupon);

        if ($coupon->is_percentage) {
            $ticketWillDiscount = $cart->tickets()->where('entrance_id', $coupon->entrance_id)->first();
            $cart->discount = (int) ($ticketWillDiscount->price * $coupon->discount / 100);
        } else {
            $cart->discount = $coupon->discount;
        }

        $cart->coupon()->associate($coupon);

        $cart->save();

        return $cart->fresh();
    }

    /**
     * @param  array                      $data
     * @param  \Modules\Cart\Models\Cart  $cart
     */
    private function setBoletoPayment(array $data, Cart &$cart)
    {
        $customer = $cart->customer;

        if (array_key_exists('address', $data)) {
            $customer->address()->create($data['address']);
            $customer->save();

            UpdateUserAddress::dispatch(\Request::bearerToken(), \Auth::id(), $data['address']);
        } else {
            $user = (new ApiService())->getUser(\Request::bearerToken())->data;

            $customer->address()->create([
                'street'      => $user->relationships->address->street,
                'number'      => $user->relationships->address->number,
                'complement'  => $user->relationships->address->complement,
                'district'    => $user->relationships->address->district,
                'postal_code' => $user->relationships->address->postal_code,
                'city'        => $user->relationships->address->city,
                'state'       => $user->relationships->address->state,
            ]);

            $customer->save();
        }

        $cart->save();
    }

    /**
     * @param  array                      $data
     * @param  \Modules\Cart\Models\Cart  $cart
     */
    private function setCardPayment(array $data, Cart &$cart)
    {
        $data['card']['parcel'] = (int) (floatval($data['card']['parcel']) * 100);

        $card = $cart->card()->create(array_except($data['card'], ['holder']));

        $holder = $card->holder()->create(array_except($data['card']['holder'], ['phone']));

        $holder->address()->create($data['address']);

        $holder->phone()->create([
            'area_code' => substr($data['card']['holder']['phone'], 0, 2),
            'phone'     => substr($data['card']['holder']['phone'], 2),
        ]);

        $holder->save();

        $card->save();

        $cart->save();
    }
}
