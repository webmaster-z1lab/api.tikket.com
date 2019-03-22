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
use Modules\Order\Events\OrderCreated;
use Modules\Order\Models\Order;
use Modules\Sale\Models\Sale;

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
     * @return \Modules\Order\Models\Order|null
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
            'channel'        => Order::ONLINE_CHANNEL,
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

        if (!$data['is_free']) {
            event(new OrderCreated($order->id));
        } else {
            $order->status = Order::PAID;
            $order->save();
        }

        return $order;
    }

    /**
     * @param array $data
     *
     * @return \Modules\Order\Models\Order|null
     */
    public function createBySale(array $data)
    {
        $tickets = [];
        $amount = 0;
        $fee = 0;

        $ticketsByEntrance = collect($data['tickets'])->groupBy('entrance');
        foreach ($ticketsByEntrance as $entrance_id => $items) {
            $entrance = Entrance::find($entrance_id);

            if (!$entrance->is_free) {
                $amount += ($entrance->available->value * $items->count());
                $fee += ($entrance->available->fee * $items->count());
            }

            foreach ($items as $ticket)
                $tickets[] = [
                    'entrance_id' => $ticket['entrance'],
                    'entrance'    => $entrance->name,
                    'lot'         => $ticket['lot'],
                    'value'       => $entrance->is_free ? 0 : $entrance->available->value,
                    'fee'         => $entrance->is_free ? 0 : $entrance->available->fee,
                    'discount'    => 0,
                    'name'        => $ticket['name'],
                    'document'    => $ticket['document'],
                    'email'       => $ticket['email'],
                    'code'        => $ticket['code'],
                ];
        }

        $admin = \Gate::allows('master', $data['event']) || \Gate::allows('organizer', $data['event']);

        $order = Order::create([
            'amount'         => $amount,
            'fee'            => $fee,
            'status'         => Order::PAID,
            'channel'        => $admin ? Order::ADMIN_CHANNEL : Order::PDV_CHANNEL,
        ]);

        $order->event()->associate($data['event']);
        $order->tickets()->createMany($tickets);

        $user = \Auth::user();

        if ($admin) {
            $order->administrator()->create([
                'user_id'  => $user->id,
                'name'     => $user->name,
                'email'    => $user->email,
                'document' => $user->document,
            ]);
        } else {
            $order->sale_point()->create([
                'user_id'  => $user->id,
                'name'     => $user->name,
                'email'    => $user->email,
                'document' => $user->document,
            ]);
        }

        $order->save();

        $order->update([
            'fee_percentage' => $order->event->fee_percentage,
            'fee_is_hidden'  => $order->event->fee_is_hidden,
        ]);

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
