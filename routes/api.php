<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MpesaPaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// M-Pesa Payment Webhook Routes
// These routes should be publicly accessible for M-Pesa to send callbacks
Route::post('/mpesa/webhook', [MpesaPaymentController::class, 'webhook'])->name('mpesa.webhook');
Route::post('/mpesa/stk-callback', [MpesaPaymentController::class, 'stkCallback'])->name('mpesa.stk-callback');
