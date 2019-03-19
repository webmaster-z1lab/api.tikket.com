<?php

Route::middleware('api.v:1,report')
    ->prefix('v1')
    ->group(function () {
        Route::prefix('events/{event}/reports')->group(function () {
            Route::get('sales', 'ReportController@valueSales');

            Route::get('canceled', 'ReportController@canceledSales');

            Route::get('tickets', 'ReportController@soldTickets');

            Route::get('fee', 'ReportController@feeValues');
        });
    });
