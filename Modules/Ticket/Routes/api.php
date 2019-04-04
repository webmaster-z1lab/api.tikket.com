<?php

Route::middleware('api.v:1,ticket')->prefix('v1')->group(function ()
{
    Route::apiResource('tickets', 'TicketController')->only(['index', 'show']);
});
