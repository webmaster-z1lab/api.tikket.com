<?php

use Modules\Order\Models\Order;

Route::view('/', 'cover');

Route::get('test', static function () {
    $order = Order::find('5d7a8b57805c1e39b40039db');

    $params = [
        'action'  => config('app.main_site_url').'/meus-ingressos',
        'text'    => 'Acabamos de receber o seu pedido. Para concluí-lo basta pagar o boleto bancário.',
        'title'   => 'Pedido recebido com sucesso!',
        'icon'    => 'fas fa-barcode',
        'color'   => 'info',
        'sent_at' => now(),
    ];

    return new \App\Mail\Customer\OrderApprovedMail($order, $params);
});
