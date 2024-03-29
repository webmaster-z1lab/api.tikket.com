<?php

Route::middleware('api.v:1,cart')->prefix('v1')->group(static function ()
{
    Route::get('carts', 'CartController@find')->name('carts.index');

    Route::post('carts', 'CartController@store')->name('carts.store');

    Route::patch('carts/{cart}/tickets', 'CartController@tickets')->name('carts.tickets');

    Route::patch('carts/{cart}/payment', 'CartController@payment')->name('carts.payment');

    Route::patch('carts/{cart}/coupon', 'CartController@coupon')->name('carts.coupon');
});
