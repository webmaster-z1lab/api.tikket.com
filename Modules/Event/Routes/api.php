<?php

Route::middleware('api.v:1,event')
    ->prefix('v1')
    ->group(function () {
        Route::apiResource('events', 'EventController')->except(['show']);

        Route::prefix('events')->as('events.')->group(function () {
            Route::get('{event}', 'EventController@show')->name('show')->where('event', '\b[0-9a-fA-F]{24}\b');
            Route::get('{url}', 'EventController@findByUrl');
        });

        Route::prefix('events/{event}')->group(function () {
            Route::patch('address', 'EventController@address');

            Route::apiResource('entrances', 'EntranceController');

            Route::apiResource('producers', 'ProducerController')->except(['index', 'update']);
        });

        Route::get('categories', 'CategoryController@index');

        Route::get('producers', 'ProducerController@getByUser');
    });
