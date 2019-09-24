<?php

namespace App\Notifications\Customer;

use App\Mail\Customer\BoletoReceivedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class BoletoReceived extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param  \Modules\Order\Models\Customer  $notifiable
     *
     * @return array
     */
    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * @param  \Modules\Order\Models\Order  $notifiable
     *
     * @return \App\Mail\Customer\BoletoReceivedMail
     */
    public function toMail($notifiable): BoletoReceivedMail
    {
        return (new BoletoReceivedMail($notifiable, $this->toArray($notifiable)))->to($notifiable->customer->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  \Modules\Order\Models\Order  $notifiable
     *
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            'action'  => $notifiable->boleto->url,
            'text'    => 'Acabamos de receber o seu pedido e ele está sendo processado pelo nosso gateway de pagamentos.',
            'title'   => 'Pedido recebido com sucesso!',
            'icon'    => 'fas fa-shopping-cart',
            'color'   => 'info',
            'sent_at' => now(),
        ];
    }
}
