<?php

namespace App\Notifications\Customer;

use App\Mail\Customer\AvailableTicketMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AvailableTicket extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  \Modules\Order\Models\Customer  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  \Modules\Order\Models\Customer  $notifiable
     *
     * @return \App\Mail\Customer\AvailableTicketMail
     */
    public function toMail($notifiable)
    {
        return (new AvailableTicketMail())->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  \Modules\Order\Models\Customer  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
