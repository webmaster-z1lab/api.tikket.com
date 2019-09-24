<?php

namespace App\Notifications\Customer;

use App\Mail\Customer\OrderReversedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OrderReversed extends Notification implements ShouldQueue
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
     * @return \App\Mail\Customer\OrderReversedMail
     */
    public function toMail($notifiable): OrderReversedMail
    {
        return (new OrderReversedMail($notifiable, $this->toArray($notifiable)))->to($notifiable->customer->email);
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
            'action'  => config('app.main_site_url')."/order/{$notifiable->id}",
            'text'    => 'O pedido de extono do seu pedido foi realizado com sucesso.',
            'title'   => 'Pedido extornado com sucesso',
            'icon'    => 'fas fa-undo',
            'color'   => 'info',
            'sent_at' => now(),
        ];
    }
}
