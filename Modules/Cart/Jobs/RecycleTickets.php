<?php

namespace Modules\Cart\Jobs;

use App\Traits\AvailableEntrances;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Cart\Models\Cart;
use Modules\Event\Models\Entrance;

class RecycleTickets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, AvailableEntrances;

    public $deleteWhenMissingModels = TRUE;
    /**
     * @var \Modules\Cart\Models\Cart
     */
    protected $cart;
    /**
     * @var \Modules\Event\Models\Entrance
     */
    protected $entrance;

    /**
     * RecycleTickets constructor.
     *
     * @param \Modules\Cart\Models\Cart $cart
     */
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        foreach ($this->cart->tickets as $ticket) {
            if (NULL === $this->entrance || $this->entrance->id !== $ticket->entrance_id) {
                $this->entrance = Entrance::find($ticket->entrance_id);
            }

            $this->incrementAvailable($this->entrance, Entrance::RESERVED);
        }
    }
}
