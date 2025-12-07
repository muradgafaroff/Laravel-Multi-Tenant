<?php

use Illuminate\Support\Facades\Route;
use Modules\Tenants\Http\Controllers\TenantRegisterController;
use Modules\Tenants\Http\Controllers\TenantsController;

Route::get('tenants', [TenantsController::class, 'index']);
Route::post('tenant/register', [TenantRegisterController::class, 'register']);
