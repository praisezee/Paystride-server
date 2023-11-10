<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\SomethingController;
use App\Http\Controllers\MerchantController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([
    'prefix' => 'v1',
    'middleware' => 'with_paystride_api_key'
], function () {

    Route::post('/just/an/example', [SomethingController::class, 'justAnExample']);

    
});

// Use apiResource for Merchant resource
Route::apiResource('merchants', MerchantController::class);
Route::post('/verifyemail', [MerchantController::class, 'verifyEmail']);