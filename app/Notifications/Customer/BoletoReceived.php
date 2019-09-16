<?php

namespace App\Notifications\Customer;

use App\Mail\Customer\BoletoReceivedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Modules\Order\Models\Order;

class BoletoReceived extends Notification implements ShouldQueue
{
    use Queueable;
    /**
     * @var \Modules\Order\Models\Order
     */
    public $order;

    /**
     * Create a new notification instance.
     *
     * @param  \Modules\Order\Models\Order  $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
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
     * @return \App\Mail\Customer\BoletoReceivedMail
     */
    public function toMail($notifiable): BoletoReceivedMail
    {
        return (new BoletoReceivedMail($this->order, $this->toArray($notifiable)))->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  \Modules\Order\Models\Customer  $notifiable
     *
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            'action'  => $this->order->boleto->url,
            'text'    => 'Acabamos de receber o seu pedido e ele está sendo processado pelo nosso gateway de pagamentos.',
            'title'   => 'Pedido recebido com sucesso!',
            'icon'    => 'fas fa-shopping-cart',
            'color'   => 'info',
            'sent_at' => now(),
        ];
    }
}
