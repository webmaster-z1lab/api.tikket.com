<?php

Route::middleware('api')->get('ticket', function () {
    return response()->json('ok');
});
