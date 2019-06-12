<?php

namespace Modules\Order\Jobs;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Str;
use Modules\Order\Events\ReadyBoleto;
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
        $data = [];
        $data['amount'] = $this->order->amount - $this->order->discount;
        $data['order_id'] = $this->order->id;
        $data['hash'] = $this->order->hash;
        $data['ip'] = $this->order->ip;
        $data['type'] = $this->order->type;
        $data['items'] = [];
        $total = 0;

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

            $total += count($tickets);
        }

        $data['customer'] = [
            'user_id'  => $this->order->customer->user_id,
            'name'     => $this->order->customer->name,
            'email'    => $this->order->customer->email,
            'document' => $this->order->customer->document,
            'phone'    => [
                'area_code' => $this->order->customer->phone->area_code,
                'phone'     => $this->order->customer->phone->phone,
            ],
        ];

        if ($data['type'] === 'boleto') {
            $data['description'] = $total === 1 ? $total . ' entrada para ' : $total . ' entradas para ';
            $data['description'] = Str::limit($data['description'] . $event->name, 100);
            $data['customer']['address'] = [
                'street'      => $this->order->customer->address->street,
                'number'      => $this->order->customer->address->number,
                'complement'  => $this->order->customer->address->complement,
                'district'    => $this->order->customer->address->district,
                'postal_code' => $this->order->customer->address->postal_code,
                'city'        => $this->order->customer->address->city,
                'state'       => $this->order->customer->address->state,
            ];
        } else {
            $data['card'] = [
                'brand'        => $this->order->card->brand,
                'number'       => $this->order->card->number,
                'token'        => $this->order->card->token,
                'installments' => $this->order->card->installments,
                'parcel'       => $this->order->card->parcel,
                'holder'       => [
                    'name'       => $this->order->card->holder->name,
                    'document'   => $this->order->card->holder->document,
                    'birth_date' => $this->order->card->holder->birth_date->format('Y-m-d'),
                    'address'    => [
                        'street'      => $this->order->card->holder->address->street,
                        'number'      => $this->order->card->holder->address->number,
                        'complement'  => $this->order->card->holder->address->complement,
                        'district'    => $this->order->card->holder->address->district,
                        'postal_code' => $this->order->card->holder->address->postal_code,
                        'city'        => $this->order->card->holder->address->city,
                        'state'       => $this->order->card->holder->address->state,
                    ],
                    'phone'      => [
                        'area_code' => $this->order->card->holder->phone->area_code,
                        'phone'     => $this->order->card->holder->phone->phone,
                    ],
                ],
            ];
        }

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
                'url'      => $transaction['attributes']['payment_method']['boleto']['url'],
                'barcode'  => $transaction['attributes']['payment_method']['boleto']['barcode'],
                'due_date' => Carbon::createFromFormat(Carbon::W3C, $transaction['attributes']['payment_method']['boleto']['due_date']),
            ]);

            $this->order->save();

            broadcast(new ReadyBoleto($this->order));

        } else {
            $this->order->save();
        }
    }
}
