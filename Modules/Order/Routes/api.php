<?php

Route::middleware('api.v:1,order')
    ->prefix('v1')
    ->group(function () {
        Route::apiResource('orders', 'OrderController')->only(['store', 'show', 'destroy']);

        Route::patch('orders/{order}/status', 'OrderController@status')->name('orders.status');

        Route::post('sales', 'SaleController@store');
    });
