<?php

namespace Modules\Order\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderReversed extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var \Modules\Order\Models\Order
     */
    public $order;

    /**
     * @var array
     */
    public $url = [
        'link' => 'https://tikket.com.br',
        'text' => 'Refazer compra',
    ];

    /**
     * @var array
     */
    public $image = [
        'source' => 'https://cdn.z1lab.com.br/images/undraw/png/undraw_alert.png',
        'text'   => 'Estorno realizado com sucesso',
    ];

    /**
     * @var string
     */
    public $subject = 'Estorno do pedido';

    public $description = 'O estorno do seu pedido foi realizado com sucesso';

    /**
     * NeedsUpdatePayment constructor.
     *
     * @param $order
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.order.order-reversed');
    }
}
