<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 29/01/2019
 * Time: 18:42
 */

namespace Modules\Order\Repositories;

use App\Notifications\Customer\OrderReceived;
use App\Traits\AvailableCoupons;
use App\Traits\AvailableEntrances;
use Modules\Cart\Models\Cart;
use Modules\Event\Models\Entrance;
use Modules\Event\Models\Event;
use Modules\Order\Jobs\SendToPayment;
use Modules\Order\Models\Order;
use Modules\Ticket\Jobs\CancelTickets;
use Modules\Ticket\Jobs\CreateTickets;

class OrderRepository
{
    use AvailableEntrances, AvailableCoupons;

    /**
     * @param  string  $id
     *
     * @return \Modules\Order\Models\Order
     */
    public function find(string $id): Order
    {
        $order = Order::find($id);

        if ($order === NULL) abort(404);

        return $order;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getByUser(): \Illuminate\Support\Collection
    {
        $past = \Request::query('past', FALSE);

        $past = $past === 'false' ? FALSE : (bool) $past;

        if ($past) {
            return Order::where('customer.user_id', \Auth::id())
                ->whereHas('event', static function ($query) {
                    $query->whereIn('status', [Event::CANCELED, Event::FINALIZED]);
                })->latest()->get();
        }

        return Order::where('customer.user_id', \Auth::id())
            ->whereHas('event', static function ($query) {
                $query->whereNotIn('status', [Event::CANCELED, Event::FINALIZED]);
            })->latest()->get();
    }

    /**
     * @param  string  $cart_id
     * @param  string  $ip
     *
     * @return \Modules\Order\Models\Order|null
     * @throws \Exception
     */
    public function createByCart(string $cart_id, string $ip): ?Order
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
        $order->bags()->createMany($data['bags']);

        $order->coupon()->associate($cart->coupon);

        /** @var  \App\Models\User $user */
        $user = \Auth::user();

        $customer = $order->customer()->create([
            'user_id'  => $user->id,
            'name'     => $user->name,
            'email'    => $user->email,
            'document' => $data['customer']['document'],
        ]);

        $customer->phone()->create($data['customer']['phone']);
        $customer->save();

        switch ($data['type']) {
            case 'credit_card':
                $card = $order->card()->create(array_except($data['card'], ['holder']));
                $holder = $card->holder()->create(array_except($data['card']['holder'], ['address', 'phone']));
                $holder->phone()->create($data['card']['holder']['phone']);
                $holder->address()->create($data['card']['holder']['address']);

                $holder->save();
                $card->save();
                break;
            case 'boleto':
                $customer->address()->create($data['customer']['address']);
                $customer->save();
                $order->boleto()->create();
        }

        $order->save();

        if (!$data['is_free']) {
            if ($data['type'] === 'credit_card') {
                $order = $order->fresh();
                $order->notify(new OrderReceived);
            }

            SendToPayment::dispatch($order);
        } else {
            foreach ($order->bags as $bag) {
                $this->incrementSold(Entrance::find($bag->entrance_id), $bag->amount);
            }

            $order->status = Order::PAID;
            $order->save();
            CreateTickets::dispatch($order);
        }

        return $order;
    }

    /**
     * @param  array  $data
     *
     * @return \Modules\Order\Models\Order|null
     */
    public function createBySale(array $data): ?Order
    {
        $tickets = [];
        $amount = 0;
        $fee = 0;

        $ticketsByEntrance = collect($data['tickets'])->groupBy('entrance');
        foreach ($ticketsByEntrance as $entrance_id => $items) {
            $entrance = Entrance::find($entrance_id);

            $this->incrementSold($entrance, $items->count(), Entrance::AVAILABLE);

            if (!$entrance->is_free) {
                $amount += ($entrance->available->value * $items->count());
                $fee += ($entrance->available->fee * $items->count());
            }

            foreach ($items as $ticket) {
                $tickets[] = [
                    'entrance_id' => $ticket['entrance'],
                    'entrance'    => $entrance->name,
                    'lot'         => $ticket['lot'],
                    'value'       => $entrance->is_free ? 0 : $entrance->available->value,
                    'fee'         => $entrance->is_free ? 0 : $entrance->available->fee,
                    'name'        => $ticket['name'],
                    'document'    => $ticket['document'],
                    'email'       => $ticket['email'],
                    'code'        => $ticket['code'],
                ];
            }
        }

        $admin = \Gate::allows('master', $data['event']) || \Gate::allows('organizer', $data['event']);

        $order = Order::create([
            'amount'   => $amount,
            'fee'      => $fee,
            'discount' => 0,
            'status'   => Order::PAID,
            'channel'  => $admin ? Order::ADMIN_CHANNEL : Order::PDV_CHANNEL,
        ]);

        $order->event()->associate($data['event']);
        $order->tickets()->createMany($tickets);

        /** @var  \App\Models\User $user */
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

        CreateTickets::dispatch($order);

        return $order;
    }

    /**
     * @param  array   $data
     * @param  string  $id
     *
     * @return \Modules\Order\Models\Order|null
     */
    public function setStatus(array $data, string $id): ?Order
    {
        $order = $this->find($id);

        $this->checkStatusForEntrances($order, $data['status']);

        $this->checkStatusForCoupons($order, $data['status']);

        $changed = $order->update($data);

        if ($changed) {
            if ($order->status === Order::PAID) {
                CreateTickets::dispatch($order);
            } elseif ($order->status === Order::REVERSED) {
                CancelTickets::dispatch($order);
            }
        }

        return $order;
    }

    /**
     * @param  \Modules\Cart\Models\Cart  $cart
     */
    private function setWaitingEntrances(Cart $cart): void
    {
        foreach ($cart->bags as $bag) {
            $entrance = Entrance::find($bag->entrance_id);
            $this->incrementWaiting($entrance);
        }
    }

    /**
     * @param  \Modules\Order\Models\Order  $order
     * @param  string                       $status
     */
    private function checkStatusForEntrances(Order $order, string $status): void
    {
        foreach ($order->tickets->groupBy('entrance_id') as $entrance_id => $tickets) {
            $entrance = Entrance::find($entrance_id);

            switch ($status) {
                case Order::PAID:
                    $this->incrementSold($entrance, $tickets->count());
                    break;
                case Order::CANCELED:
                    $this->incrementAvailable($entrance, Entrance::WAITING, $tickets->count());
                    break;
                case Order::REVERSED:
                    $this->incrementAvailable($entrance, Entrance::SOLD, $tickets->count());
                    break;
                default:
            }
        }
    }

    /**
     * @param  \Modules\Order\Models\Order  $order
     * @param  string                       $status
     */
    private function checkStatusForCoupons(Order $order, string $status): void
    {
        if ($order->coupon()->exists()) {
            switch ($status) {
                case Order::CANCELED:
                case Order::REVERSED:
                    $this->decrementUsed($order->coupon);
                    break;
                default:
            }
        }
    }
}
