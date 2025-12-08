<?php

use Illuminate\Support\Facades\Route;
use Modules\Tasks\Http\Controllers\TaskController;
use Modules\Tasks\Http\Controllers\CommentController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Illuminate\Support\Facades\Cache;

Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'auth:sanctum'
])->group(function () {


Route::get('/redis-test', function () {
    try {
        Cache::put('test_key', 'Redis OK!', 10);
        return response()->json([
            'cache_value' => Cache::get('test_key')
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
});

    /** Static routes MUST be before apiResource */
    Route::get('/tasks/status-count', [TaskController::class, 'statusCount']);
    Route::put('/tasks/{id}/status', [TaskController::class, 'updateStatus']);
    Route::put('/tasks/{id}/assign', [TaskController::class, 'assign']);

    /** Resource route */
    Route::apiResource('tasks', TaskController::class);

    /** Comments */
    Route::group(['prefix' => 'tasks/{taskId}'], function () {
        Route::post('/comments', [CommentController::class, 'store']);
        Route::get('/comments', [CommentController::class, 'index']);
        Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
    });
});


