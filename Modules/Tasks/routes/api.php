<?php

use Illuminate\Support\Facades\Route;
use Modules\Tasks\Http\Controllers\TaskController;
use Modules\Tasks\Http\Controllers\CommentController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'auth:sanctum'
])->group(function () {

    
    Route::apiResource('tasks', TaskController::class);

    Route::put('/tasks/{id}/status', [TaskController::class, 'updateStatus']);

 
    Route::put('/tasks/{id}/assign', [TaskController::class, 'assign']);

    
Route::group(['prefix' => 'tasks/{taskId}'], function () {
    
    Route::post('/comments', [CommentController::class, 'store']);
    Route::get('/comments', [CommentController::class, 'index']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
    
   });

   
});
