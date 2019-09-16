<?php

namespace App\Notifications\Customer;

use App\Mail\Customer\OrderReceivedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Modules\Order\Models\Order;

class OrderReceived extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var \Modules\Order\Models\Order
     */
    public $order;
    /**
     * @var int
     */
    public $delay = 30;

    /**
     * OrderReceived constructor.
     *
     * @param  \Modules\Order\Models\Order  $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @param  \Modules\Order\Models\Customer  $notifiable
     *
     * @return array
     */
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * @param $notifiable
     *
     * @return \App\Mail\Customer\OrderReceivedMail
     */
    public function toMail($notifiable): OrderReceivedMail
    {
        return (new OrderReceivedMail($this->order, $this->toArray($notifiable)))->to($notifiable->email);
    }

    /**
     * @param  \Modules\Order\Models\Customer  $notifiable
     *
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            'action'  => config('app.main_site_url')."/meus-pedidos/{$this->order->id}",
            'text'    => 'Acabamos de receber o seu pedido e ele está sendo processado pelo nosso gateway de pagamentos.',
            'title'   => 'Pedido recebido com sucesso!',
            'icon'    => 'fas fa-shopping-cart',
            'color'   => 'info',
            'sent_at' => now(),
        ];
    }
}
