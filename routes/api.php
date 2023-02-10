<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiAuthentication as Authentication;

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
    Route::post('register', [Authentication::class, 'register']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('logout')->group(function () {
            Route::post('current', [Authentication::class, 'logout']);
            Route::post('all', [Authentication::class, 'logoutAll']);
        });
    });
    Route::post('forgot-password', [Authentication::class, 'forgotPassword']);
    Route::post('reset-password', [Authentication::class, 'resetPassword']);
});

Route::middleware('auth:sanctum')->group(function () {
});
