<?php

namespace App\Notifications\Organizer;

use App\Mail\Organizer\EventPublishedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Modules\Event\Models\Event;

class EventPublished extends Notification implements ShouldQueue
{
    use Queueable;
    /**
     * @var \Modules\Event\Models\Event
     */
    public $event;

    /**
     * EventPublished constructor.
     *
     * @param  \Modules\Event\Models\Event  $event
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  \Modules\Event\Models\Permission  $notifiable
     *
     * @return array
     */
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * @param  \Modules\Event\Models\Permission  $notifiable
     *
     * @return \App\Mail\Organizer\EventPublishedMail
     */
    public function toMail($notifiable): EventPublishedMail
    {
        return (new EventPublishedMail($this->event, $this->toArray($notifiable)))->to($notifiable->email);
    }

    /**
     * @param  \Modules\Event\Models\Permission  $notifiable
     *
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            'action'  => config('app.main_site_url')."/evento/{$this->event->id}",
            'text'    => "O seu evento {$this->event->name} acaba de ser publicado e está pronto para venda de ingressos.",
            'title'   => 'Evento publicado com sucesso',
            'icon'    => 'far fa-calendar-check',
            'color'   => 'info',
            'sent_at' => now(),
        ];
    }
}
