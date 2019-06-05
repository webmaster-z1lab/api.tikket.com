<?php

Route::view('/', 'cover');

Route::get('test', function () {
    broadcast(new \Modules\Order\Events\ReadyBoleto());

    return 'test';
});
