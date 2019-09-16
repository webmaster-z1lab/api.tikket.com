<?php

namespace App\Notifications\Customer;

use App\Mail\Customer\OrderFailedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Modules\Order\Models\Order;

class OrderFailed extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var \Modules\Order\Models\Order
     */
    public $order;

    /**
     * OrderFailed constructor.
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
     * @param  @param \Modules\Order\Models\Customer $notifiable
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
     * @return \App\Mail\Customer\OrderFailedMail
     */
    public function toMail($notifiable): OrderFailedMail
    {
        return (new OrderFailedMail($this->order, $this->toArray($notifiable)))->to($notifiable->email);
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
            'action'  => config('app.main_site_url')."/evento/{$this->order->event_id}",
            'text'    => 'O pagamento do seu pedido foi negado pela operadora de cartão.',
            'title'   => 'Falha no pagamento do seu pedido',
            'icon'    => 'far fa-times-circle',
            'color'   => 'danger',
            'sent_at' => now(),
        ];
    }
}
