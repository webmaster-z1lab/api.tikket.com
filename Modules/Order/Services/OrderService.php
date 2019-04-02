<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 27/03/2019
 * Time: 15:13
 */

namespace Modules\Order\Services;


use GuzzleHttp\Client;
use Modules\Order\Models\Order;
use Modules\Order\Repositories\OrderRepository;

class OrderService
{
    /**
     * @var \Modules\Order\Repositories\OrderRepository
     */
    private $repository;

    /**
     * OrderService constructor.
     *
     * @param \Modules\Order\Repositories\OrderRepository $repository
     */
    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function cancel(string $id)
    {
        $order = $this->repository->find($id);

        if (!\Gate::allows('master', $order->event) && !\Gate::allows('organizer', $order->event))
            if (now()->diffInDays($order->created_at) > 7 || now()->diffInDays($order->event->starts_at) < 2)
                abort(403, "You can't cancel this order.");


        if (!filled($order->transaction_id)) {
            if ($order->channel === Order::ONLINE_CHANNEL)
                abort(400, "This order doesn't have a transaction.");

            if ($order->status === Order::PAID)
                $this->repository->setStatus(['status' => Order::REVERSED], $id);
            else
                abort(400, 'The order status is incompatible.');
        } else {
            $client = new Client(['base_uri' => config('payment.server')]);

            $credential = \OpenID::getClientToken();

            if (!$credential)
                abort(400, 'Not possible to generate the client credential.');

            $client->delete('api/v1/transactions/' . $order->transaction_id, [
                'headers' => [
                    'Authorization' => "Bearer $credential",
                ]
            ]);
        }

        return TRUE;
    }
}
