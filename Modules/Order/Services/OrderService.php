<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 27/03/2019
 * Time: 15:13
 */

namespace Modules\Order\Services;


use GuzzleHttp\Client;
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
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function cancel(string $id)
    {
        $order = $this->repository->find($id);

        if (!\Gate::allows('master', $order->event) && !\Gate::allows('organizer', $order->event))
            if (now()->diffInDays($order->created_at) > 7 || $order->event->starts_at->diffInDays($order->created_at) < 2)
                abort(403, "You can't cancel this order.");

        $client = new Client(['base_uri' => config('payment.server')]);

        $credential = \OpenID::getClientToken();

        if (!$credential)
            abort(400,'Not possible to generate the client credential.');

        return $client->delete('api/v1/transactions/' . $order->transaction_id, [
            'headers' => [
                'Authorization' => "Bearer $credential",
            ]
        ]);
    }
}
