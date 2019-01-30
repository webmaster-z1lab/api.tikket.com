<?php

namespace Modules\Order\Listeners;

use GuzzleHttp\Client;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Event\Repositories\EntranceRepository;
use Modules\Order\Events\OrderCreated;
use Modules\Order\Repositories\OrderRepository;

class SendToPayment
{
    /**
     * @var \Modules\Order\Repositories\OrderRepository
     */
    protected $repository;
    /**
     * @var \Modules\Event\Repositories\EntranceRepository
     */
    private $entranceRepository;

    /**
     * Create the event listener.
     *
     * @param \Modules\Order\Repositories\OrderRepository    $repository
     * @param \Modules\Event\Repositories\EntranceRepository $entranceRepository
     */
    public function __construct(OrderRepository $repository, EntranceRepository $entranceRepository)
    {
        $this->repository = $repository;
        $this->entranceRepository = $entranceRepository;
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

        $data = $order->toArray();
        unset($data['tickets']);
        $data['items'] = [];

        foreach ($order->tickets()->groupBy('entrance_id')->get() as $entrance_id => $tickets) {
            $entrance = $this->entranceRepository->find($entrance_id);
            $data['items'][] = [
                'item_id'     => $entrance_id,
                'description' => $entrance->name,
                'quantity'    => count($tickets),
                'amount'      => 10000,
            ];
        }

        $client = new Client(['base_uri' => config('payment.server')]);

        $credential = \OpenID::getClientToken();

        if (!$credential)
            throw new \Exception('Not possible to generate the client credential.');

        $response = $client->post('api/v1/transactions', [
            'headers' => [
                'Authorization' => "Bearer $credential",
            ],
            'json'    => $data,
        ]);

        $transaction_id = json_decode($response->getBody(), TRUE)['data']['id'];
        $order->transaction_id = $transaction_id;
        $order->save();
    }
}
