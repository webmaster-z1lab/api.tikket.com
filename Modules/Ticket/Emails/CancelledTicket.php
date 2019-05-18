<?php

namespace Modules\Ticket\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Ticket\Models\Ticket;

class CancelledTicket extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var \Modules\Ticket\Models\Ticket
     */
    public $ticket;

    /**
     * @var array
     */
    public $url = [
        'link' => 'https://tikket.com.br',
        'text' => 'Ir para o site',
    ];

    /**
     * @var array
     */
    public $image = [
        'source' => 'https://d35c048n9fix3e.cloudfront.net/images/undraw/png/undraw_alert.png',
        'text'   => 'Ingresso cancelado',
    ];

    /**
     * @var string
     */
    public $subject = 'Ingresso cancelado';

    public $description = 'O seu ingresso foi cancelado.';

    /**
     * Create a new message instance.
     *
     * @param  \Modules\Ticket\Models\Ticket  $ticket
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.ticket.ticket-cancelled');
    }
}
