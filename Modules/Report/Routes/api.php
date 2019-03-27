<?php

Route::middleware('api.v:1,report')
    ->prefix('v1')
    ->group(function () {
        Route::prefix('events/{event}/reports')->group(function () {
            Route::get('sales', 'ReportController@valueSales');

            Route::get('canceled', 'ReportController@canceledSales');

            Route::get('canceled-values', 'ReportController@canceledValues');

            Route::get('pending-values', 'ReportController@pendingValues');

            Route::get('sold-tickets', 'ReportController@soldTickets');

            Route::get('pending-tickets', 'ReportController@pendingTickets');

            Route::get('canceled-tickets', 'ReportController@canceledTickets');

            Route::get('net-value', 'ReportController@amountValues');

            Route::get('orders', 'OrderController@get');

            Route::get('participants', 'ParticipantController@get');

            Route::get('summary', 'SummaryController@basic');

            Route::prefix('sale-points/{pdv}')
                ->group(function () {
                    Route::get('tickets', 'ReportController@salePointTickets');

                    Route::get('values', 'ReportController@salePointValues');
                });
        });
    });
