<?php

use App\Http\Controllers\api\v1\CustomerController;
use App\Http\Controllers\api\v1\InvoiceController;
use App\Http\Controllers\api\v1\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
*/


/*
Route::group([
    "middleare" => ["auth:sanctum"],
    'prefix' => 'v1',
    'namespace' => 'App\Http\Controllers\api\v1'
], function() {
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('invoices', InvoiceController::class);
});
*/


Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\api\v1'], function() {
    Route::post('/signup', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/refresh_token', [AuthController::class, 'refreshToken']);
});


// api/v1
// eg: http://localhost:8888/laravel-api-with-jwt/public/api/v1/customers

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\api\v1', 'middleware' => ['auth:sanctum']], function() {
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('invoices', InvoiceController::class);

    Route::post('invoices/bulk', ['uses' => 'InvoiceController@bulkStore']);
});


