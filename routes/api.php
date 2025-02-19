<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FormController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\NewsletterController;
use App\Http\Controllers\Api\StripeWebhookController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post("/logout", [AuthController::class, "logout"]);
    Route::get("/profile", [AuthController::class, "profile"]);
    Route::put('/profile/update', [AuthController::class, 'updateProfile']);
    Route::delete('/profile/delete', [AuthController::class, 'deleteAccount']);
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);
    Route::delete('/cart', [CartController::class, 'deleteAll']);
    Route::get('/user/orders', [OrderController::class, 'getAllOrdersForUser']);
    Route::get('/admin/orders', [OrderController::class, 'getAllOrders']);
});

Route::post("/login", [AuthController::class, "login"]);
Route::post("/register", [AuthController::class, "register"]);

Route::get('/services', [ServiceController::class, 'index']);
Route::post('/service', [ServiceController::class, 'store']);
Route::get('/service/{id}', [ServiceController::class, 'show']);
Route::put('/service/{id}', [ServiceController::class, 'update']);
Route::delete('/service/{id}', [ServiceController::class, 'destroy']);

Route::get('/tests', [TestController::class, 'index']);
Route::post('/test', [TestController::class, 'store']);
Route::get('/test/{id}', [TestController::class, 'show']);
Route::put('/test/{id}', [TestController::class, 'update']);
Route::delete('/test/{id}', [TestController::class, 'destroy']);

Route::post('/checkout', [CheckoutController::class, 'createSession']);
Route::post('/admin/order', [OrderController::class, 'placeOrder']);
Route::post('/contact', [FormController::class, 'sendContactForm']);

Route::post('/subscribe', action: [NewsletterController::class, 'subscribe']);
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);