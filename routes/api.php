<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SomethingController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\PaymentPointController;
use App\Http\Controllers\SettlementAccountController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WebhookController;
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
Route::apiResource('login',LoginController::class);
Route::resource('login',LoginController::class);

Route::apiResource('staff',StaffController::class);
Route::resource('staff',StaffController::class);


Route::get('/merchants/all', [MerchantController::class, 'index']);
Route::post('/login',[LoginController::class,'login']);
// Route::get('/logout',[LoginController::class,'logout']);

Route::resource('merchants', MerchantController::class);


// Add specific routes after apiResource to avoid conflicts
Route::post('/verifyemail', [MerchantController::class, 'verifyEmail']);
Route::post('/merchants/resend-otp', [MerchantController::class, 'resendOtp']);
Route::post('/merchants/reset-password', [MerchantController::class, 'reset_password']);
Route::post('/merchants/forgot-password', [MerchantController::class, 'forgot_password']);

Route::get('/merchants/{id}/users', [StaffController::class, 'show_staff']);
Route::get('/merchants/users/{id}',[StaffController::class,'getSingleStaff']);
Route::post('/merchants/users', [StaffController::class, 'create_staff']);
Route::post('/merchants/users/verifyemail',[StaffController::class,'verifyEmail']);
Route::post('/merchants/users/resend-otp',[StaffController::class,'resendOtp']);
Route::put('/merchants/users/{id}/roles', [StaffController::class, 'update_role']);
Route::delete('merchants/users/{id}',[StaffController::class,'deleteStaff']);

Route::post('/merchants/payment-points',[PaymentPointController::class, 'create']);
Route::get('/merchants/{id}/payment-points',[PaymentPointController::class, 'getAllPaymentPoint']);
Route::get('/merchants/payment-points/{id}',[PaymentPointController::class, 'getSinglePaypoint']);
Route::put('/merchants/payment-points/{id}',[PaymentPointController::class,'editPaymentPoint']);
Route::delete('/merchants/payment-points/{id}',[PaymentPointController::class,'deletePaymentPoint']);

Route::get('merchant/{id}/settlements',[SettlementAccountController::class,'getAllSettlementAccounts']);// returns all settlement account associated to a merchant
Route::post('/merchants/settlements',[SettlementAccountController::class,'createSettlementAccount']);// Creates a new settlement account
Route::put('/merchants/settlements/{id}',[SettlementAccountController::class, 'editSettlementAccount']);
Route::delete('/merchants/settlements/{id}',[SettlementAccountController::class, 'deleteSettlementAccount']);

Route::post('/api/support/submit-request',[SupportController::class,'submitRequest']);
Route::get('/api/support/past-issues',[SupportController::class,'getPastIssues']);

Route::resource('/transactions',TransactionController::class);

Route::post('/webhook', [WebhookController::class, 'handleWebhook']);
