<?php

use App\Mail\Customer\AvailableTicketMail;
use Modules\Order\Models\Order;

Route::view('/', 'cover');

Route::get('test', function (){
    return new AvailableTicketMail(Order::first());
});
