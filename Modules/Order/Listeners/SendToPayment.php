<?php

namespace Modules\Order\Listeners;

use GuzzleHttp\Client;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Order\Events\OrderCreated;

class SendToPayment
{
    protected $repository;

    /**
     * Create the event listener.
     *
     * @param $repository
     */
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle the event.
     *
     * @param  OrderCreated $event
     *
     * @return void
     * @throws \Exception
     */
    public function handle(OrderCreated $event)
    {
        $order = $this->repository->find($event->getOrder());

        $client = new Client(['base_uri' => config('payment.server')]);

        $credential = \OpenID::getClientToken();

        if (!$credential)
            throw new \Exception('Not possible to generate the client credential.');

        $response = $client->post('api/v1/transactions', [
            'headers' => [
                'Authorization' => "Bearer $credential",
            ],
            'json'    => $order->toArray(),
        ]);

        $transaction_id = json_decode($response->getBody(), TRUE)['data']['id'];
        $order->transaction_id = $transaction_id;
        $order->save();
    }
}
