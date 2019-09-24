<?php

namespace App\Notifications\Customer;

use App\Mail\Customer\OrderCancelledMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OrderCancelled extends Notification implements ShouldQueue
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
     * @return \App\Mail\Customer\OrderCancelledMail
     */
    public function toMail($notifiable): OrderCancelledMail
    {
        return (new OrderCancelledMail($notifiable, $this->toArray($notifiable)))->to($notifiable->customer->email);
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
            'text'    => 'O seu pedido foi cancelado. Acesse o site para detalhes.',
            'title'   => 'Pedido cancelado',
            'icon'    => 'fas fa-times-circle',
            'color'   => 'info',
            'sent_at' => now(),
        ];
    }
}
