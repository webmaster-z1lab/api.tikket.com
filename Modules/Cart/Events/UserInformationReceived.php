<?php

namespace Modules\Cart\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserInformationReceived
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var string
     */
    private $token;
    /**
     * @var string
     */
    private $document;
    /**
     * @var string
     */
    private $phone;
    /**
     * @var string
     */
    private $user_id;

    /**
     * Create a new event instance.
     *
     * @param string $token
     * @param string $user_id
     * @param string $document
     * @param string $phone
     */
    public function __construct(string $token, string $user_id, string $document, string $phone)
    {
        $this->token = $token;
        $this->document = $document;
        $this->phone = $phone;
        $this->user_id = $user_id;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getDocument(): string
    {
        return $this->document;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->user_id;
    }
}
