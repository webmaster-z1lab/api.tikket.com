<?php

namespace Modules\Cart\Jobs;

use App\Traits\AvailableCoupons;
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
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, AvailableEntrances, AvailableCoupons;

    /**
     * @var \Modules\Cart\Models\Cart
     */
    protected $cart;

    /**
     * RecycleTickets constructor.
     *
     * @param  string  $cart
     */
    public function __construct(string $cart)
    {
        $this->cart = Cart::find($cart);
    }

    /**
     * @throws \Exception
     */
    public function handle(): void
    {
        if (NULL !== $this->cart) {
            foreach ($this->cart->bags as $bag) {
                $entrance = Entrance::find($bag->entrance_id);
                $this->incrementAvailable($entrance, Entrance::RESERVED, $bag->amount);
            }

            if ($this->cart->coupon()->exists()) {
                $this->decrementUsed($this->cart->coupon);
            }

            $this->cart->status = Cart::RECYCLED;
            $this->cart->save();
            $this->cart->delete();
        }
    }
}
