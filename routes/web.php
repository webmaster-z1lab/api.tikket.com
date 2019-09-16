<?php

use Modules\Order\Models\Order;

Route::view('/', 'cover');

Route::get('test', static function () {
    $order = Order::find('5d77aa3f805c1e228c00093d');

    $params = [
        'action'  => config('app.main_site_url')."/order/{$order->id}",
        'text'    => 'O pedido de extono do seu pedido foi realizado com sucesso.',
        'title'   => 'Pedido extornado com sucesso',
        'icon'    => 'fas fa-undo',
        'color'   => 'info',
        'sent_at' => now(),
    ];

    return new \App\Mail\Customer\OrderCancelledMail($order, $params);
});
