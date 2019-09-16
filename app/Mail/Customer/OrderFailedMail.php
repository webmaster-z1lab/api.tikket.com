<?php

namespace App\Mail\Customer;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Order\Models\Order;

class OrderFailedMail extends Mailable
{
    use Queueable, SerializesModels;
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
        'text' => 'Comprar novamente',
    ];
    /**
     * @var array
     */
    public $image = [
        'source' => 'https://d35c048n9fix3e.cloudfront.net/images/undraw/png/undraw_cancel.png',
        'text'   => 'Falha ao realizar pedido',
    ];

    /**
     * OrderFailedMail constructor.
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
        return $this->view('emails.customer.order-failed');
    }
}
