<?php

namespace Modules\Order\Jobs;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Order\Models\Order;

class SendToPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \Modules\Order\Models\Order
     */
    private $order;

    /**
     * Create a new job instance.
     *
     * @param  \Modules\Order\Models\Order  $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order->fresh();
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        $data = $this->order->toArray();
        $data['amount'] += $data['fee'];
        $data['amount'] -= $data['discount'];
        unset($data['tickets'], $data['fee'], $data['discount']);
        $data['items'] = [];

        $event = $this->order->event;

        foreach ($this->order->tickets()->groupBy('entrance_id') as $entrance_id => $tickets) {
            $entrance = $event->entrances()->find($entrance_id);
            $lot = $entrance->lots()->where('number', $tickets->first()->lot)->first();
            $data['items'][] = [
                'item_id'     => $entrance_id,
                'description' => $entrance->name,
                'quantity'    => count($tickets),
                'amount'      => $lot->price,
            ];
        }

        $data['order_id'] = $this->order->id;

        $data['card']['holder']['birth_date'] = $data['card']['holder']['birth_date']->toDateTime()->format('Y-m-d');

        $client = new Client(['base_uri' => config('payment.server')]);

        $credential = \OpenID::getClientToken();

        if (!$credential) {
            throw new \Exception('Not possible to generate the client credential.');
        }

        $response = $client->post('api/v1/transactions', [
            'headers' => [
                'Authorization' => "Bearer $credential",
            ],
            'json'    => $data,
        ]);

        $transaction = json_decode($response->getBody(), TRUE)['data'];
        $this->order->transaction_id = $transaction['id'];
        if ($data['type'] === 'boleto') {
            $this->order->boleto->update([
                'url'      => $transaction['payment_method']['boleto']['url'],
                'barcode'  => $transaction['payment_method']['boleto']['barcode'],
                'due_date' => Carbon::createFromFormat(Carbon::W3C, $transaction['payment_method']['boleto']['due_date']),
            ]);
        }
        $this->order->save();
    }
}
