<?php

namespace App\Notifications\Customer;

use App\Mail\Customer\OrderReceivedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OrderReceived extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @param  \Modules\Order\Models\Order  $notifiable
     *
     * @return array
     */
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * @param  \Modules\Order\Models\Order  $notifiable
     *
     * @return \App\Mail\Customer\OrderReceivedMail
     */
    public function toMail($notifiable): OrderReceivedMail
    {
        return (new OrderReceivedMail($notifiable, $this->toArray($notifiable)))->to($notifiable->customer->email);
    }

    /**
     * @param  \Modules\Order\Models\Order  $notifiable
     *
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            'action'  => config('app.main_site_url')."/meus-pedidos/{$notifiable->id}",
            'text'    => 'Acabamos de receber o seu pedido e ele está sendo processado pelo nosso gateway de pagamentos.',
            'title'   => 'Pedido recebido com sucesso!',
            'icon'    => 'fas fa-shopping-cart',
            'color'   => 'info',
            'sent_at' => now(),
        ];
    }
}
