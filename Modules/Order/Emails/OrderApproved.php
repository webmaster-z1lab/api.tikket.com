<?php

namespace Modules\Order\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Order\Models\Order;

class OrderApproved extends Mailable
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
        'text' => 'Meus ingressos',
    ];

    /**
     * @var array
     */
    public $image = [
        'source' => 'https://d35c048n9fix3e.cloudfront.net/images/undraw/png/undraw_super_thank_you.png',
        'text'   => 'Compra confirmada',
    ];

    /**
     * @var string
     */
    public $subject = 'Compra confirmada';

    /**
     * @var string
     */
    public $description = 'A sua compra no Tikket foi confirmada e você já tenho acesso ao seu ingresso.';

    /**
     * NeedsUpdatePayment constructor.
     *
     * @param \Modules\Order\Models\Order $order
     */
    public function __construct(Order $order)
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
        return $this->view('emails.order.order-approved');
    }
}
