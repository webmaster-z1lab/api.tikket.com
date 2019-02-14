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

class RecycleCart implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, AvailableEntrances;

    public $deleteWhenMissingModels = TRUE;
    /**
     * @var \Modules\Cart\Models\Cart
     */
    protected $cart;

    /**
     * RecycleTickets constructor.
     *
     * @param \Modules\Cart\Models\Cart $cart
     */
    public function __construct(Cart $cart)
    {
        $this->cart = $cart->fresh();
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        foreach ($this->cart->bags as $bag) {
            $entrance = Entrance::find($bag->entrance_id);
            $this->incrementAvailable($entrance, Entrance::RESERVED, $bag->amount);
        }

        $this->cart->status = Cart::RECYCLED;
        $this->cart->save();
        $this->cart->delete();
    }
}
