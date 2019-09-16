<?php

namespace App\Notifications\Customer;

use App\Mail\Customer\OrderCancelledMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Modules\Order\Models\Order;

class OrderCancelled extends Notification implements ShouldQueue
{
    use Queueable;
    /**
     * @var \Modules\Order\Models\Order
     */
    public $order;

    /**
     * OrderCancelled constructor.
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
     * @param  \Modules\Order\Models\Customer  $notifiable
     *
     * @return \App\Mail\Customer\OrderCancelledMail
     */
    public function toMail($notifiable): OrderCancelledMail
    {
        return (new OrderCancelledMail($this->order, $this->toArray($notifiable)))->to($notifiable->email);
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
            'text'    => 'O seu pedido foi cancelado. Acesse o site para detalhes.',
            'title'   => 'Pedido cancelado',
            'icon'    => 'fas fa-times-circle',
            'color'   => 'info',
            'sent_at' => now(),
        ];
    }
}
