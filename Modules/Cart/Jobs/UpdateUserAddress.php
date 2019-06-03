<?php

namespace Modules\Cart\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateUserAddress implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $user_id;

    /**
     * @var array
     */
    private $address;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $token, string $user_id, array $address)
    {
        $this->token = $token;
        $this->user_id = $user_id;
        $this->address = $address;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client(['base_uri' => env('AUTH_SERVER')]);

        $client->post('api/v1/users/'.$this->user_id.'/phones', [
            'headers' => [
                'Authorization' => 'Bearer '.$this->token,
            ],
            'json'    => $this->address,
        ]);
    }
}
