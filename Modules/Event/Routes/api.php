<?php

Route::middleware('api.v:1,event')
    ->prefix('v1')
    ->group(function () {
        Route::get('actions/user_ip', 'SearchController@getGeoIp');

        Route::apiResource('coupons', 'CouponController')->except(['index']);

        Route::apiResource('events', 'EventController')->except(['show']);

        Route::prefix('events')->as('events.')->group(function () {
            Route::get('search', 'SearchController@index')->name('search');
            Route::get('featured', 'SearchController@featured')->name('featured');
            Route::get('{event}', 'EventController@show')->name('show')->where('event', '\b[0-9a-fA-F]{24}\b');
            Route::get('{url}', 'EventController@findByUrl');
        });

        Route::prefix('events/{event}')->group(function () {
            Route::patch('address', 'EventController@address');

            Route::patch('finalize', 'EventController@finilize');

            Route::patch('publish', 'EventController@publish');

            Route::patch('unpublish', 'EventController@unpublish');

            Route::patch('cancel', 'EventController@cancel');

            //Route::patch('fee', 'EventController@fee');

            Route::get('sale-points', 'PermissionController@salePoints');

            Route::get('my-permissions', 'PermissionController@getLevels');

            Route::get('my-permission', 'PermissionController@getByUserAndEvent');

            Route::apiResource('entrances', 'EntranceController');

            Route::apiResource('producers', 'ProducerController')->except(['index', 'update']);

            Route::apiResource('permissions', 'PermissionController')->except(['update']);

            Route::get('coupons', 'CouponController@getByEvent');
        });

        Route::get('categories', 'CategoryController@index');

        Route::get('producers', 'ProducerController@getByUser');

        Route::get('permissions', 'PermissionController@getByUser');
    });


