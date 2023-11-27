<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\SomethingController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\PaymentPointController;
use App\Http\Controllers\StaffController;
use App\Models\Staff;

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

   //endpoints to be wrapped around the api key


});
Route::post('/generate-api-key', [ApiKeyController::class, 'generateApiKey']);


// Use apiResource for Merchant resource
Route::apiResource('merchants', MerchantController::class)->except(['create', 'edit']);
Route::get('/merchants/all', [MerchantController::class, 'index']);

// Add specific routes after apiResource to avoid conflicts
Route::post('/verifyemail', [MerchantController::class, 'verifyEmail']);
Route::post('/merchants/resend-otp', [MerchantController::class, 'resendOtp']);
Route::post('/merchants/reset-password', [MerchantController::class, 'reset_password']);
Route::post('/merchants/forgot-password', [MerchantController::class, 'forgot_password']);

Route::get('/merchants/{id}/users', [StaffController::class, 'show_staff']);
Route::post('/merchants/users', [StaffController::class, 'create_staff']);
Route::put('/merchants/users/{id}/roles', [StaffController::class, 'update_role']);

Route::post('/merchant/payment-points',[PaymentPointController::class, 'create']);
Route::get('/merchant/{id}/payment-points',[PaymentPointController::class, 'getAllPaymentPoint']);
Route::get('/merchant/payment-points/{id}',[PaymentPointController::class, 'getSinglePaypoint']);
