<?php

Route::middleware('api.v:1,cart')->prefix('v1')->group(function ()
{
    Route::get('carts', 'CartController@find')->name('carts.index');

    Route::post('carts', 'CartController@store')->name('carts.store');

    Route::put('carts/{cart}/tickets', 'CartController@tickets')->name('carts.tickets');

    Route::put('carts/{cart}/card', 'CartController@card')->name('carts.card');
});
