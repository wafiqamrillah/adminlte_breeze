<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;

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

Route::name('api.')->group(function() {
    Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::middleware('guest')->group(function() {
        Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');
        Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');
        Route::name('password')->group(function() {
            Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('.email');
            Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('.store');
        });
    });
    
    Route::middleware(['auth'])->group(function() {
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
        Route::middleware(['throttle:6,1'])->name('verification')->group(function() {
            Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed'])->name('.verify');
            Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->name('.send');
        });
    });
});