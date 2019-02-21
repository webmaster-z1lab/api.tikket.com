<?php

namespace Modules\Event\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Event\Models\Entrance;

class LotWillEnd extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var \Modules\Event\Models\Entrance
     */
    public $entrance;

    public $url = [
        'link' => 'https://tikket.com.br',
        'text' => 'Lote irá encerrar',
    ];

    public $image = [
        'source' => '',
        'text'   => 'Ir para o lote',
    ];

    public $subject = 'Lote irá encerrar';

    public $description = 'O lote da entrada irá se encerrar em breve.';

    /**
     * Create a new message instance.
     *
     * @param \Modules\Event\Models\Entrance $entrance
     */
    public function __construct(Entrance $entrance)
    {
        $this->entrance = $entrance;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.event.lot-will-end');
    }
}
