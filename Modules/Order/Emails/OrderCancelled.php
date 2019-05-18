<?php

namespace Modules\Order\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderCancelled extends Mailable
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
        'source' => 'https://d35c048n9fix3e.cloudfront.net/images/undraw/png/undraw_alert.png',
        'text'   => 'Pedido cancelado',
    ];

    /**
     * @var string
     */
    public $subject = 'Pedido cancelado';

    public $description = 'Seu pedido foi cancelado.';

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
        return $this->view('emails.order.order-canceled');
    }
}
