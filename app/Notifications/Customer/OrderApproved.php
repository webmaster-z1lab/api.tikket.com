<?php

namespace App\Notifications\Customer;

use App\Mail\Customer\OrderApprovedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OrderApproved extends Notification implements ShouldQueue
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
     * @return \App\Mail\Customer\OrderApprovedMail
     */
    public function toMail($notifiable): OrderApprovedMail
    {
        return (new OrderApprovedMail($notifiable, $this->toArray($notifiable)))->to($notifiable->customer->email);
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
        $status = $notifiable->type === 'credit_card' ? 'aprovado' : 'confirmado';

        return [
            'action'  => config('app.main_site_url').'/meus-ingressos',
            'text'    => "O pagamento do seu pedido acaba de ser {$status}. O(s) ingresso(s) já estão disponíveis para impressão.",
            'title'   => 'Pedido concluído com sucesso!',
            'icon'    => 'far fa-check-square',
            'color'   => 'info',
            'sent_at' => now(),
        ];
    }
}
