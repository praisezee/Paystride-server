<?php

use App\Http\Controllers\MerchantController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::prefix('/api')->group(function(){
//     Route::post('/merchant/signup',[MerchantController::class, 'register']);
//     Route::post('/merchant/forget-password',[MerchantController::class, 'forget_password']);
//     Route::post('/merchant/reset-password',[MerchantController::class, 'reset_password']);
//     Route::post('/merchant/verify-email', MerchantController::class, 'verifyEmail');
//     Route::resource('transactions', TransactionController::class)->except(['destroy']);
// });

Route::get('/', function () {
    return view('welcome');
});
