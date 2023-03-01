<?php

use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Auth\Authentication as Authentication;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Public\SetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\ClassController;
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
    Route::prefix('set')->group(function () {
        Route::post('/', [SetController::class, 'store']);
        Route::get('/{id}', [SetController::class, 'show']);
        Route::put('/{id}', [SetController::class, 'update']);
        Route::delete('/{id}', [SetController::class, 'destroy']);
        Route::post('/import', [SetController::class, 'import']);
        Route::get('/export/{id}', [SetController::class, 'export']);
    });
    Route::prefix('class')->group(function () {
        Route::post('/addSet/{id}', [ClassController::class, 'addSet']);
        Route::post('/addTeacher/{id}', [ClassController::class, 'addTeacher']);
        Route::post('/addStudent/{id}', [ClassController::class, 'addStudent']);
        Route::post('/addFolder/{id}', [ClassController::class, 'addFolder']);
        Route::delete('/{class_id}/teacher/{teacher_id}', [ClassController::class, 'deleteTeacher']);
        Route::delete('/{class_id}/student/{student_id}', [ClassController::class, 'deleteStudent']);
        Route::delete('/{class_id}/set/{set_id}', [ClassController::class, 'deleteSet']);
        Route::delete('/{class_id}/folder/{folder_id}', [ClassController::class, 'deleteFolder']);
        Route::post('/', [ClassController::class, 'store']);
        Route::put('/{id}', [ClassController::class, 'update']);
        Route::delete('/{id}', [ClassController::class, 'destroy']);
        Route::get('/', [ClassController::class, 'index']);
        Route::get('/{id}', [ClassController::class, 'show']);
    });
});
