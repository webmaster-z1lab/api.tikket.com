<?php

namespace Modules\Event\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Event\Models\Event;

class EventSoon extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var \Modules\Event\Models\Event
     */
    public $event;

    public $url = [
        'link' => 'https://tikket.com.br',
        'text' => 'Evento ocorrerá em breve',
    ];

    public $image = [
        'source' => '',
        'text'   => 'Ir para o evento',
    ];

    public $subject = 'Lembrete de evento';

    public $description = 'O evento irá acontecer em breve, não esqueça.';

    /**
     * Create a new message instance.
     *
     * @param \Modules\Event\Models\Event $event
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.event.reminder');
    }
}
