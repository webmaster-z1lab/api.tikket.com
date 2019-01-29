<?php

Route::middleware('api.v:1,event')->prefix('v1')->group(function ()
{
    Route::prefix('events/{event}')->group(function ()
    {
        Route::apiResource('entrances', 'EntranceController');
    });

    Route::apiResource('events', 'EventController');
});
