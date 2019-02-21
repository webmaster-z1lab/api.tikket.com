<?php

namespace Modules\Order\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderFailed extends Mailable
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
        'text'   => 'Falha ao realizar compra',
    ];

    /**
     * @var string
     */
    public $subject = 'Falha ao realizar pedido';

    public $description = 'Infelizmente não conseguimos concluir o seu pedido. A operadora do seu cartão de crédito não aprovou o pagamento da sua compra.';

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
        return $this->view('emails.order.order-failed');
    }
}
