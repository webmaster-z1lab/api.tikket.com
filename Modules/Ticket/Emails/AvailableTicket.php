<?php

namespace Modules\Ticket\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Ticket\Models\Ticket;

class AvailableTicket extends Mailable
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
        'text' => 'Ir para o ingresso',
    ];

    /**
     * @var array
     */
    public $image = [
        'source' => 'https://cdn.z1lab.com.br/images/undraw/png/undraw_alert.png',
        'text'   => 'Ingresso disponível no site',
    ];

    /**
     * @var string
     */
    public $subject = 'Ingresso disponível no site';

    public $description = 'O seu ingresso já está disponível no site.';

    /**
     * Create a new message instance.
     *
     * @return void
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
        return $this->view('view.name');
    }
}
