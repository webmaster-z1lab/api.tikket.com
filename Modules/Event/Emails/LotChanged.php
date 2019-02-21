<?php

namespace Modules\Event\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Event\Models\Entrance;

class LotChanged extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var \Modules\Event\Models\Entrance
     */
    public $entrance;

    public $url = [
        'link' => 'https://tikket.com.br',
        'text' => 'Mudança de lote',
    ];

    public $image = [
        'source' => '',
        'text'   => 'Ir para o lote',
    ];

    public $subject = 'Alteração de lote';

    public $description = 'O lote da entrada foi alterado.';

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
        return $this->view('emails.event.lot-changed');
    }
}
