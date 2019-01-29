<?php

Route::middleware('api')->get('orders', function () {
   return response()->json('orders');
});
