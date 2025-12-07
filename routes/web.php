<?php

use Illuminate\Support\Facades\Route;

 Route::get('/', function () {

     dD(base_path('Modules/Tenants/database/migrations'));

     return view('welcome');
 });
