<?php

Route::middleware('api.v:1,order')->prefix('v1')->group(function ()
{
   Route::post('orders', 'OrderController@store')->name('orders.store');
});
