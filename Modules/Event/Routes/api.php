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
        });

        Route::get('categories', 'CategoryController@index');

        Route::get('producers', 'ProducerController@getByUser');

        Route::get('test', function () {
            $entrance = \Modules\Event\Models\Entrance::find('5c6ab664e7a6cd0ca0006917');

            $entrance->available->increment('sold');

            \Modules\Event\Jobs\LockEntrance::dispatchNow($entrance, $entrance->available->lot);
            \Modules\Event\Jobs\LockEvent::dispatchNow($entrance->event);

            return response()->json($entrance->event->fresh());
        });
    });


