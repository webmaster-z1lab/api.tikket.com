<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 29/01/2019
 * Time: 18:42
 */

namespace Modules\Order\Repositories;

use App\Traits\AvailableCoupons;
use App\Traits\AvailableEntrances;
use Modules\Cart\Models\Cart;
use Modules\Event\Models\Entrance;
use Modules\Order\Models\Order;

class OrderRepository
{
    use AvailableEntrances, AvailableCoupons;

    /**
     * @param string $id
     *
     * @return null|\Modules\Order\Models\Order
     */
    public function find(string $id)
    {
        $order = Order::find($id);

        if ($order === NULL) abort(404);

        return $order;
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
        $cart = Cart::find($cart_id);

        $data = $cart->toArray();

        $this->setWaitingEntrances($cart);
        $cart->status = Cart::FINISHED;
        $cart->save();
        $cart->delete();

        $order = Order::create([
            'hash'           => $data['hash'],
            'ip'             => $ip,
            'type'           => $data['type'],
            'amount'         => $data['amount'],
            'discount'       => $data['discount'],
            'fee_percentage' => $data['fee_percentage'],
            'fee_is_hidden'  => $data['fee_is_hidden'],
            'fee'            => $data['fee'],
        ]);

        $order->event()->associate($data['event_id']);
        $order->tickets()->createMany($data['tickets']);

        $order->coupon()->associate($cart->coupon);

        $user = \Auth::user();

        $costumer = $order->costumer()->create([
            'user_id'  => $user->id,
            'name'     => $user->name,
            'email'    => $user->email,
            'document' => $data['costumer']['document'],
        ]);

        $costumer->phone()->create($data['costumer']['phone']);
        $costumer->save();

        $card = $order->card()->create(array_except($data['card'], ['holder']));
        $holder = $card->holder()->create(array_except($data['card']['holder'], ['address', 'phone']));
        $holder->phone()->create($data['card']['holder']['phone']);
        $holder->address()->create($data['card']['holder']['address']);

        $holder->save();
        $card->save();
        $order->save();

        return $order->fresh();
    }

    /**
     * @param array  $data
     * @param string $id
     *
     * @return \Modules\Order\Models\Order|null
     */
    public function setStatus(array $data, string $id)
    {
        $order = $this->find($id);

        $this->checkStatusForEntrances($order, $data['status']);

        $this->checkStatusForCoupons($order, $data['status']);

        $order->status = $data['status'];
        $order->save();

        return $order->fresh();
    }

    /**
     * @param \Modules\Cart\Models\Cart $cart
     */
    private function setWaitingEntrances(Cart $cart)
    {
        foreach ($cart->bags as $bag) {
            $entrance = Entrance::find($bag->entrance_id);
            $this->incrementWaiting($entrance);
        }
    }

    /**
     * @param \Modules\Order\Models\Order $order
     * @param string                      $status
     */
    private function checkStatusForEntrances(Order $order, string $status)
    {
        foreach ($order->bags as $bag) {
            $entrance = Entrance::find($bag->entrance_id);

            switch ($status) {
                case Order::PAID:
                    $this->incrementSold($entrance, $bag->amount);
                    break;
                case Order::CANCELED:
                case Order::REVERSED:
                    $this->incrementAvailable($entrance, Entrance::WAITING, $bag->amount);
                    break;
                default:
            }
        }
    }

    /**
     * @param \Modules\Order\Models\Order $order
     * @param string                      $status
     */
    private function checkStatusForCoupons(Order $order, string $status)
    {
        switch ($status) {
            case Order::CANCELED:
            case Order::REVERSED:
                $this->decrementUsed($order->coupon);
                break;
            default:
        }
    }
}
