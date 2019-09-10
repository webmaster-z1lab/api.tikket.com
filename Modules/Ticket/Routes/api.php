<?php

Route::middleware('api.v:1,ticket')->prefix('v1')->group(static function ()
{
    Route::apiResource('tickets', 'TicketController')->only(['index', 'show']);
});
