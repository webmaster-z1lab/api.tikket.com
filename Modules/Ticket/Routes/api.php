<?php

Route::middleware('api')->get('tickets', function () {
    return response()->json('tickets');
});
