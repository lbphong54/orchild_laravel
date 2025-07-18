<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\RestaurantTypeController;
use App\Http\Controllers\Api\TableController;
use App\Http\Controllers\Auth\ForgotPasswordController;
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

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
Route::post('/forgot-password', [AuthController::class, 'sendResetOtp']);
Route::post('/resend-forgot-otp', [AuthController::class, 'resendResetOtp']);
Route::get('/restaurants/{id}/related', [RestaurantController::class, 'related']);

// Restaurant routes
Route::get('/restaurants', [RestaurantController::class, 'index']);
Route::get('/restaurants/{id}', [RestaurantController::class, 'show']);
Route::get('/restaurants/{id}/reviews', [ReviewController::class, 'index']);
Route::get('/restaurant-types', [RestaurantTypeController::class, 'index']);
Route::get('/restaurants-table', [TableController::class, 'index']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::post('/reservations/{id}/cancel', [ReservationController::class, 'cancel']);
    Route::post('/restaurants/{id}/reviews', action: [ReviewController::class, 'store']);
    Route::get('/reservations/history', [ReservationController::class, 'history']);
    Route::get('/reservations/{id}/qr-code', [ReservationController::class, 'getQrCode']);
});

// Password reset routes
// Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);