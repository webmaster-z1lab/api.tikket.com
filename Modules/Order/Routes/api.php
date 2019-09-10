<?php

Route::middleware('api.v:1,order')
    ->prefix('v1')
    ->group(static function () {
        Route::apiResource('orders', 'OrderController')->except(['update']);

        Route::patch('orders/{order}/status', 'OrderController@status')->name('orders.status');

        Route::post('sales', 'SaleController@store');
    });
