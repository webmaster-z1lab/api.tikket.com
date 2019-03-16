<?php

namespace Modules\Cart\Listeners;

use GuzzleHttp\Client;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Cart\Events\UserInformationReceived;

class UpdateUser
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new Client(['base_uri' => env('AUTH_SERVER')]);
    }

    /**
     * Handle the event.
     *
     * @param  \Modules\Cart\Events\UserInformationReceived $event
     *
     * @return void
     */
    public function handle(UserInformationReceived $event)
    {
        $this->client->patch('api/v1/users/' . $event->getUserId() . '/cpf', [
            'headers' => [
                'Authorization' => 'Bearer ' . $event->getToken(),
            ],
            'json'    => ['document' => $event->getDocument()],
        ]);

        $this->client->post('api/v1/users/' . $event->getUserId() . '/phones', [
            'headers' => [
                'Authorization' => 'Bearer ' . $event->getToken(),
            ],
            'json'    => [
                'phone'       => $event->getPhone(),
                'is_whatsapp' => FALSE,
            ],
        ]);
    }
}
