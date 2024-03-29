<?php

namespace App\Mail\Customer;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Modules\Order\Models\Order;

class BoletoReceivedMail extends Mailable
{
    use Queueable;
    /**
     * @var \Modules\Order\Models\Order
     */
    public $order;
    /**
     * @var array
     */
    public $params;
    /**
     * @var array
     */
    public $button = [
        'link' => '',
        'text' => 'Visualizar boleto',
    ];
    /**
     * @var array
     */
    public $image = [
        'source' => 'https://d35c048n9fix3e.cloudfront.net/images/undraw/png/undraw_super_thank_you.png',
        'text'   => 'Pedido realizado',
    ];

    /**
     * BoletoReceivedMail constructor.
     *
     * @param  \Modules\Order\Models\Order  $order
     * @param  array                        $params
     */
    public function __construct(Order $order, array $params)
    {
        $this->order = $order;
        $this->params = $params;
        $this->subject = $this->image['text'] = $params['title'];
        $this->button['link'] = $params['action'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        return $this->view('emails.customer.boleto-received');
    }
}
