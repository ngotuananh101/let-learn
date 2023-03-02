<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Authentication as Authentication;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SetController as AdminSetController;
use App\Http\Controllers\Admin\FolderController as AdminFolderController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Public\SetController;
use App\Http\Controllers\Public\FolderController;
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
Route::get('/meta', [SettingController::class, 'meta']);
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::prefix('analytics')->middleware(['permissions:admin.analytics'])->group(function () {
            Route::get('google', [AnalyticsController::class, 'getAnalytics']);
            Route::get('local', function () {
                return response()->json([
                    'message' => 'Hello World'
                ]);
            });
        });
        Route::prefix('settings')->group(function () {
            Route::get('/', [SettingController::class, 'index']);
            Route::post('/{key}', [SettingController::class, 'update']);
        })->middleware(['permissions:admin.settings']);
        Route::resource('set', AdminSetController::class)->middleware(['permissions:admin.sets']);
        Route::resource('folder', AdminFolderController::class)->middleware(['permissions:admin.folders']);
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
    Route::prefix('class')->group(function () {
        Route::middleware(['checkUserInClass:all'])->group(function () {
            Route::post('/addSet/{id}', [ClassController::class, 'addSet']);
            Route::post('/addTeacher/{id}', [ClassController::class, 'addTeacher'])->middleware(['checkUserInClass:teacher']);
            Route::post('/addStudent/{id}', [ClassController::class, 'addStudent'])->middleware(['checkUserInClass:teacher']);
            Route::post('/addFolder/{id}', [ClassController::class, 'addFolder']);
            Route::delete('/{class_id}/teacher/{teacher_id}', [ClassController::class, 'deleteTeacher'])->middleware(['checkUserInClass:teacher']);
            Route::delete('/{class_id}/student/{student_id}', [ClassController::class, 'deleteStudent'])->middleware(['checkUserInClass:teacher']);
            Route::delete('/{class_id}/set/{set_id}', [ClassController::class, 'deleteSet'])->middleware(['checkUserInClass:teacher']);
            Route::delete('/{class_id}/folder/{folder_id}', [ClassController::class, 'deleteFolder'])->middleware(['checkUserInClass:teacher']);
            Route::put('/{id}', [ClassController::class, 'update'])->middleware(['checkUserInClass:teacher']);
            Route::delete('/{id}', [ClassController::class, 'destroy'])->middleware(['checkUserInClass:teacher']);
            Route::get('/{id}', [ClassController::class, 'show']);
        });
        Route::post('/', [ClassController::class, 'store']);
        Route::get('/', [ClassController::class, 'index']);
    });
});
