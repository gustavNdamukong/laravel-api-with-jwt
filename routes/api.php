<?php

use App\Http\Controllers\api\v1\CustomerController;
use App\Http\Controllers\api\v1\InvoiceController;
use App\Http\Controllers\api\v1\AuthController;
use Illuminate\Support\Facades\Route;
/*
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
*/

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\api\v1'], function() {
    Route::post('/signup', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/refresh_token', [AuthController::class, 'refreshToken']);
});


// api/v1
// eg: http://127.0.0.1:8000/api/v1/customers

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\api\v1', 'middleware' => ['auth:sanctum']], function() {
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('invoices', InvoiceController::class);

    Route::post('invoices/bulk', ['uses' => 'InvoiceController@bulkStore']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/delete-token/{tokenId}', [AuthController::class, 'deleteSingleToken']);
});


