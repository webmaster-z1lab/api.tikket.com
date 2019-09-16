<?php

namespace App\Mail\Organizer;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Event\Models\Event;

class EventPublishedMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var \Modules\Event\Models\Event
     */
    public $event;
    /**
     * @var array
     */
    public $params;
    /**
     * @var array
     */
    public $button = [
        'link' => '',
        'text' => 'Página do evento',
    ];
    /**
     * @var array
     */
    public $image = [
        'source' => 'https://d35c048n9fix3e.cloudfront.net/images/undraw/png/undraw_confirmed.png',
        'text'   => 'Evento publicado com sucesso',
    ];

    /**
     * EventPublishedMail constructor.
     *
     * @param  \Modules\Event\Models\Event  $event
     * @param  array                        $params
     */
    public function __construct(Event $event, array $params)
    {
        $this->event = $event;
        $this->params = $params;
        $this->subject = $this->image['text'] = $params['title'];
        $this->button['link'] = $params['action'];
    }

    /**
     * @return $this
     */
    public function build(): self
    {
        return $this->view('emails.organizer.event-published');
    }
}
