<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerPaymentController;
use App\Http\Controllers\PaystackWebhookController;
use App\Http\Controllers\ServiceAccessController;


// Homepage
Route::get('/', function () {
    return view('welcome');
});


// Paystack payment webhook
Route::post('/api/paystack/webhook', [
    PaystackWebhookController::class,
    'handle',
])->name('paystack.webhook');


// Routes requiring customer login
Route::middleware('auth')->group(function () {

    // Customer dashboard
    Route::get('/dashboard', [
        CustomerPaymentController::class,
        'dashboard',
    ])->name('dashboard');

    // Customer receipts
    Route::get('/my-receipts/{payment}', [
        CustomerPaymentController::class,
        'receipt',
    ])->name('payments.receipt');

    // Checkout page
    Route::get('/checkout', [
        CheckoutController::class,
        'index',
    ])->name('checkout');

    // Process checkout
    Route::post('/checkout', [
        CheckoutController::class,
        'store',
    ])->name('checkout.store');

    // Protected paid services
    Route::get('/services/{service}', [
        ServiceAccessController::class,
        'show',
    ])->whereIn('service', [
        'education',
        'programming',
        'website',
        'online',
    ])->name('services.show');

});