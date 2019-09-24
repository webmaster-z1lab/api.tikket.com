<?php

namespace App\Notifications\Customer;

use App\Mail\Customer\OrderFailedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OrderFailed extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
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
     * @return \App\Mail\Customer\OrderFailedMail
     */
    public function toMail($notifiable): OrderFailedMail
    {
        return (new OrderFailedMail($notifiable, $this->toArray($notifiable)))->to($notifiable->customer->email);
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
            'action'  => config('app.main_site_url')."/evento/{$notifiable->event_id}",
            'text'    => 'O pagamento do seu pedido foi negado pela operadora de cartão.',
            'title'   => 'Falha no pagamento do seu pedido',
            'icon'    => 'far fa-times-circle',
            'color'   => 'danger',
            'sent_at' => now(),
        ];
    }
}
