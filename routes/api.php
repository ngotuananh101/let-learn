<?php

use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Auth\Authentication as Authentication;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Public\SetController;
use App\Http\Controllers\Public\FolderController;
use Illuminate\Support\Facades\Route;


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
    Route::post('forgot-password', [Authentication::class, 'verify']);
    Route::prefix('logout')->middleware('auth:sanctum')->group(function () {
        Route::post('current', [Authentication::class, 'logout']);
        Route::post('all', [Authentication::class, 'logoutAll']);
    });
    Route::prefix('email')->group(function () {
        Route::get('verify/{id}/{hash}', [VerificationController::class, 'verifyEmail'])->middleware(['signed'])->name('verification.verify');
        Route::post('handle-verify/{id}/{hash}', [VerificationController::class, 'handleVerifyEmail'])->middleware(['auth:sanctum']);
        Route::post('resend', [VerificationController::class, 'resendVerifyEmail'])->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');
    });
    Route::post('forgot-password', [Authentication::class, 'forgotPassword']);
    Route::post('reset-password', [Authentication::class, 'resetPassword']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::prefix('analytics')->group(function () {
            Route::get('google', [AnalyticsController::class, 'getAnalytics']);
            Route::get('local', function () {
                return response()->json([
                    'message' => 'Hello World'
                ]);
            })->middleware(['permissions:admin.analytics']);
        });
    })->middleware(['permissions:admin.access']);
    // Route set
    Route::prefix('set')->group(function () {
        Route::post('/', [SetController::class, 'store']);
        Route::get('/{id}', [SetController::class, 'show']);
        Route::put('/{id}', [SetController::class, 'update']);
        Route::delete('/{id}', [SetController::class, 'destroy']);
        Route::post('/import', [SetController::class, 'import']);
        Route::get('/export/{id}', [SetController::class, 'export']);
        Route::get('user/{id}', [SetController::class, 'showAllSetByUserId']);
        Route::get('folder/{id}', [SetController::class, 'showAllSetByFolderId']);
        Route::get('progress/{id}', [SetController::class, 'showProgressBySetId']);
    });
    // Route folder
    Route::prefix('folder')->group(function () {
        Route::post('/', [FolderController::class, 'store']);
        Route::get('/{id}', [FolderController::class, 'show']);
        Route::put('/{id}', [FolderController::class, 'update']);
        Route::delete('/{id}', [FolderController::class, 'destroy']);
        Route::get('/{id}/set', [SetController::class, 'showAllSetByFolderId']);
        Route::post('/add/{folder_id}/{set_id}', [FolderController::class, 'addSetToFolder']);
        Route::delete('/remove/{folder_id}/{set_id}', [FolderController::class, 'removeSetFromFolder']);
    });
    // Route user
    Route::prefix('user')->group(function () {
        Route::get('folder', [FolderController::class, 'showAllFolderByUserId']);
        Route::get('set', [SetController::class, 'showAllSetByUserId']);
    });
});
