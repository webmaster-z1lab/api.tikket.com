<?php

Route::middleware('api.v:1,event')
    ->prefix('v1')
    ->group(function () {
        Route::apiResource('coupons', 'CouponController');

        Route::apiResource('events', 'EventController')->except(['show']);

        Route::prefix('events')->as('events.')->group(function () {
            Route::get('my_events', 'EventController@getByUser');
            Route::get('{event}', 'EventController@show')->name('show')->where('event', '\b[0-9a-fA-F]{24}\b');
            Route::get('{url}', 'EventController@findByUrl');
        });

        Route::prefix('events/{event}')->group(function () {
            Route::patch('address', 'EventController@address');

            Route::patch('finalize', 'EventController@finilize');

            Route::patch('fee', 'EventController@fee');

            Route::apiResource('entrances', 'EntranceController');

            Route::apiResource('producers', 'ProducerController')->except(['index', 'update']);

            Route::apiResource('permissions', 'PermissionController')->except(['update']);

            Route::get('coupons', 'CouponController@getByEvent');
        });

        Route::get('categories', 'CategoryController@index');

        Route::get('producers', 'ProducerController@getByUser');

        Route::get('permissions', 'PermissionController@getByUser');
    });


