<?php

use App\Http\Controllers\api\v1\CustomerController;
use App\Http\Controllers\api\v1\InvoiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// api/v1
// eg: http://localhost:8888/laravel-api-with-jwt/public/v1/customers
/*
Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\api\v1'], function() {
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('invoices', InvoiceController::class);
});
*/
