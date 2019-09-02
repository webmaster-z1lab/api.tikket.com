<?php

namespace App\Mail\Customer;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Order\Models\Order;

class AvailableTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var \Modules\Order\Models\Order
     */
    public $order;
    /**
     * @var string
     */
    public $description = '';
    /**
     * @var string
     */
    public $subject = '';
    /**
     * @var array
     */
    public $image = [
        'source' => 'https://d35c048n9fix3e.cloudfront.net/images/undraw/png/undraw_hello_aeia.png',
        'text'   => 'Welcome and confirm your email',
    ];
    /**
     * @var array
     */
    public $button;

    /**
     * AvailableTicketMail constructor.
     *
     * @param  \Modules\Order\Models\Order  $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;

        $this->button = [
            'text' => 'Visualizar pedido',
            'link' => config('app.main_site_url') . 'pedidos/' . $order->id,
        ];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.customer.available-ticket');
    }
}
