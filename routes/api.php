<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Authentication as Authentication;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\SetController;

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

/**
 * Route for authentication.
 */
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [Authentication::class, 'login']);
    Route::post('forgot-password', [Authentication::class, 'verify']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('logout')->group(function () {
            Route::post('current', [Authentication::class, 'logout']);
            Route::post('all', [Authentication::class, 'logoutAll']);
        });
        Route::post('verify', [VerificationController::class, 'handleVerifyEmail']);
        Route::post('resend', [VerificationController::class, 'resendVerifyEmail']);
    });
    Route::post('forgot-password', [Authentication::class, 'forgotPassword']);
    Route::post('reset-password', [Authentication::class, 'resetPassword']);
});

Route::get('analytics', [AnalyticsController::class, 'getAnalytics']);

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('set', SetController::class);
});
