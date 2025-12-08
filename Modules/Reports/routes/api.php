<?php

use Illuminate\Support\Facades\Route;
use Modules\Reports\Http\Controllers\ReportsController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;


Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'auth:sanctum'
])->group(function () {

    Route::group(['prefix' => 'reports'], function () {
        Route::get('/weekly', [ReportsController::class, 'weeklyReport']);
        Route::get('/download-weekly', [ReportsController::class, 'downloadWeekly']);
    });
});


